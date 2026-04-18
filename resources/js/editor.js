import { Editor, Extension, InputRule } from '@tiptap/core';
import { TextStyle } from '@tiptap/extension-text-style'
import { Image } from '@tiptap/extension-image'
import { Link } from '@tiptap/extension-link'
import StarterKit from '@tiptap/starter-kit';
import Heading from '@tiptap/extension-heading'
import Bold from '@tiptap/extension-bold'
import Italic from '@tiptap/extension-italic'
import Paragraph from '@tiptap/extension-paragraph'
import { Markdown } from '@tiptap/markdown';
import { Placeholder } from "@tiptap/extension-placeholder";
import Underline from '@tiptap/extension-underline'
import FontFamily from '@tiptap/extension-font-family'
import MarkdownIt from 'markdown-it';
import { Plugin, PluginKey } from "prosemirror-state"
const trackKey = new PluginKey('track')
import { Mapping } from "prosemirror-transform"
class Span {
  constructor(from, to, commit) {
    this.from = from;
    this.to = to;
    this.commit = commit;
  }
}

class Commit {
  constructor(message, time, steps, maps) {
    this.message = message;
    this.time = time;
    this.steps = steps;
    this.maps = maps;
  }
}

class TrackState {
  constructor(blameMap, commits, uncommittedSteps, uncommittedMaps) {
    this.blameMap = blameMap;
    this.commits = commits;
    this.uncommittedSteps = uncommittedSteps;
    this.uncommittedMaps = uncommittedMaps;
  }
  applyTransform(transform) {
    let inverted = transform.steps.map((step, i) => step.invert(transform.docs[i]));
    let newBlame = updateBlameMap(this.blameMap, transform, this.commits.length);
    return new TrackState(newBlame, this.commits, this.uncommittedSteps.concat(inverted), this.uncommittedMaps.concat(transform.mapping.maps))
  }
  applyCommit(message, time) {
    if (this.uncommittedSteps.length == 0) return this
    let commit = new Commit(message, time, this.uncommittedSteps, this.uncommittedMaps)
    return new TrackState(this.blameMap, this.commits.concat([commit]), [], [])
  }
}

function updateBlameMap(map, transform, id) {
  let result = [], mapping = transform.mapping
  for (let i = 0; i < map.length; i++) {
    let span = map[i]
    let from = mapping.map(span.from, 1), to = mapping.map(span.to, -1)
    if (from < to) result.push(new Span(from, to, span.commit))
  }

  for (let i = 0; i < transform.steps.length; i++) {
    let map = transform.mapping.maps[i]
    map.forEach((_from, _to, start, end) => {
      let from = transform.mapping.slice(i + 1).map(start, 1)
      let to = transform.mapping.slice(i + 1).map(end, -1)
      if (from < to) result.push(new Span(from, to, id))
    })
  }

  return result.sort((a, b) => a.from - b.from)
}
const trackPlugin = new Plugin({
  key: trackKey,
  state: {
    init(_, instance) {
      return new TrackState([new Span(0, instance.doc.content.size, null)], [], [], []);
    },
    apply(tr, tracked) {
      if (tr.docChanged) tracked = tracked.applyTransform(tr);
      let commitMessage = tr.getMeta(trackKey);
      if (commitMessage) tracked = tracked.applyCommit(commitMessage, new Date());
      return tracked;
    }
  }
});

const TrackExtension = Extension.create({
  name: 'track',
  addProseMirrorPlugins() {
    return [trackPlugin]
  }
})
function revertCommit(commit, editor) {
  let trackState = trackKey.getState(editor.state)
  let index = trackState.commits.indexOf(commit)
  if (index == -1) return
  if (trackState.uncommittedSteps.length)
    return alert("Commit your changes first");

  let remap = new Mapping(trackState.commits.slice(index).reduce((maps, c) => maps.concat(c.maps), []))
  let tr = editor.state.tr
  for (let i = commit.steps.length - 1; i >= 0; i--) {
    let remapped = commit.steps[i].map(remap.slice(i + 1))
    if (!remapped) continue
    let result = tr.maybeStep(remapped)
    if (result.doc) remap.appendMap(remapped.getMap(), i)
  }
  if (tr.docChanged) {
    editor.view.dispatch(tr.setMeta(trackPlugin, `Revert '${commit.message}'`))
  }
}
const CustomHeading = Heading.extend({
  addAttributes() {
    return {
      style: {
        default: null,
        parseHTML: element => element.getAttribute('style'),
        renderHTML: attributes => attributes.style ? { style: attributes.style } : {},
      },
    }
  },
})

const CustomParagraph = Paragraph.extend({
  addAttributes() {
    return {
      style: {
        default: null,
        parseHTML: element => element.getAttribute('style'),
        renderHTML: attributes => attributes.style ? { style: attributes.style } : {},
      },
    }
  },
})

const CustomBold = Bold.extend({
  addAttributes() {
    return {
      style: {
        default: null,
        parseHTML: element => element.getAttribute('style'),
        renderHTML: attributes => attributes.style ? { style: attributes.style } : {},
      },
    }
  },
})

const CustomItalic = Italic.extend({
  addAttributes() {
    return {
      style: {
        default: null,
        parseHTML: element => element.getAttribute('style'),
        renderHTML: attributes => attributes.style ? { style: attributes.style } : {},
      },
    }
  },
})

const HtmlInputRules = Extension.create({
  name: 'htmlInputRules',
  addInputRules() {
    return [
      new InputRule({
        find: /<b(.*?)>(.*?)<\/b>$/g,
        handler: ({ state, range, match }) => {
          const { tr } = state;
          const styleMatch = match[1].match(/style=["'](.*?)["']/);
          const style = styleMatch ? styleMatch[1] : null;
          const content = match[2];
          const mark = this.editor.schema.marks.bold.create({ style });
          const textNode = content ? this.editor.schema.text(content, [mark]) : null;
          tr.replaceWith(range.from, range.to, textNode);
        },
      }),
      new InputRule({
        find: /<strong(.*?)>(.*?)<\/strong>$/g,
        handler: ({ state, range, match }) => {
          const { tr } = state;
          const styleMatch = match[1].match(/style=["'](.*?)["']/);
          const style = styleMatch ? styleMatch[1] : null;
          const content = match[2];
          const mark = this.editor.schema.marks.bold.create({ style });
          const textNode = content ? this.editor.schema.text(content, [mark]) : null;
          tr.replaceWith(range.from, range.to, textNode);
        },
      }),
      new InputRule({
        find: /<i(.*?)>(.*?)<\/i>$/g,
        handler: ({ state, range, match }) => {
          const { tr } = state;
          const styleMatch = match[1].match(/style=["'](.*?)["']/);
          const style = styleMatch ? styleMatch[1] : null;
          const content = match[2];
          const mark = this.editor.schema.marks.italic.create({ style });
          const textNode = content ? this.editor.schema.text(content, [mark]) : null;
          tr.replaceWith(range.from, range.to, textNode);
        },
      }),
      new InputRule({
        find: /<em(.*?)>(.*?)<\/em>$/g,
        handler: ({ state, range, match }) => {
          const { tr } = state;
          const styleMatch = match[1].match(/style=["'](.*?)["']/);
          const style = styleMatch ? styleMatch[1] : null;
          const content = match[2];
          const mark = this.editor.schema.marks.italic.create({ style });
          const textNode = content ? this.editor.schema.text(content, [mark]) : null;
          tr.replaceWith(range.from, range.to, textNode);
        },
      }),
      new InputRule({
        find: /<h1(.*?)>(.*?)<\/h1>$/g,
        handler: ({ state, range, match }) => {
          const { tr } = state;
          const styleMatch = match[1].match(/style=["'](.*?)["']/);
          const style = styleMatch ? styleMatch[1] : null;
          const content = match[2];
          const textNode = content ? this.editor.schema.text(content) : null;
          tr.replaceWith(range.from, range.to, this.editor.schema.nodes.heading.create({ level: 1, style }, textNode));
        },
      }),
      new InputRule({
        find: /<h2(.*?)>(.*?)<\/h2>$/g,
        handler: ({ state, range, match }) => {
          const { tr } = state;
          const styleMatch = match[1].match(/style=["'](.*?)["']/);
          const style = styleMatch ? styleMatch[1] : null;
          const content = match[2];
          const textNode = content ? this.editor.schema.text(content) : null;
          tr.replaceWith(range.from, range.to, this.editor.schema.nodes.heading.create({ level: 2, style }, textNode));
        },
      }),
      new InputRule({
        find: /<h3(.*?)>(.*?)<\/h3>$/g,
        handler: ({ state, range, match }) => {
          const { tr } = state;
          const styleMatch = match[1].match(/style=["'](.*?)["']/);
          const style = styleMatch ? styleMatch[1] : null;
          const content = match[2];
          const textNode = content ? this.editor.schema.text(content) : null;
          tr.replaceWith(range.from, range.to, this.editor.schema.nodes.heading.create({ level: 3, style }, textNode));
        },
      }),
      new InputRule({
        find: /<p(.*?)>(.*?)<\/p>$/g,
        handler: ({ state, range, match }) => {
          const { tr } = state;
          const styleMatch = match[1].match(/style=["'](.*?)["']/);
          const style = styleMatch ? styleMatch[1] : null;
          const content = match[2];
          const textNode = content ? this.editor.schema.text(content) : null;
          tr.replaceWith(range.from, range.to, this.editor.schema.nodes.paragraph.create({ style }, textNode));
        },
      }),
    ]
  },
})

const editorElement = document.querySelector("#editor")
if (editorElement) {
  const editor = new Editor({
    element: editorElement,
    extensions: [
      StarterKit.configure({
        heading: false,
        bold: false,
        italic: false,
        paragraph: false,
        history: true,
      }),
      CustomHeading,
      CustomParagraph,
      CustomBold,
      CustomItalic,
      Link.configure({
        openOnClick: false,
        autolink: true,
        linkOnPaste: true,
      }),
      Image,
      Markdown,
      TextStyle,
      HtmlInputRules,
      Placeholder.configure({
        emptyNodeClass: 'before:text-2xl',
        placeholder: 'Tell your story ...'
      }),
      Underline,
      FontFamily,
      TrackExtension
    ],
    editorProps: {
      handlePaste(event) {
        const text = event.clipboardData.getData('text/plain');
        const html = event.clipboardData.getData('text/html');

        if (text && !html && (text.includes('```') || text.includes('**') || text.includes('#'))) {
          const mdInstance = new MarkdownIt();
          const parsedHtml = mdInstance.render(text);
          editor.commands.insertContent(parsedHtml);
          return true;
        }
        return false;
      },
    },
    content: '',
  })

  const commitBtn = document.querySelector('#commit_btn');
  const commitMsgInput = document.querySelector('#commit_message_input');

  function takeSnapshot(message) {
    const state = editor.state;
    editor.view.dispatch(state.tr.setMeta(trackKey, message));
    console.log(`Snapshot taken: "${message}"`);
    renderCommitHistory();
  }

  commitBtn?.addEventListener('click', () => {
    const commitMessage = commitMsgInput?.value || 'Untitled snapshot';
    takeSnapshot(commitMessage);
    if (commitMsgInput) commitMsgInput.value = '';
  });

  // Revert button logic: Global revert (last commit)
  const revertBtn = document.querySelector('#revert_btn');
  revertBtn?.addEventListener('click', () => {
    const trackState = trackKey.getState(editor.state);
    const lastCommit = trackState?.commits[trackState.commits.length - 1];
    if (!lastCommit) return alert('No snapshots to revert');
    revertCommit(lastCommit, editor);
    renderCommitHistory();
  });

  // Revert button logic is now also handled per commit in renderCommitHistory
  function renderCommitHistory() {
    if (!editor || !editor.state) return;
    const trackState = trackKey.getState(editor.state);
    const historyContainer = document.querySelector('#history_container');
    if (!historyContainer) return;
    historyContainer.innerHTML = '';

    if (!trackState) {
      console.warn('TrackState not found in editor state');
      return;
    }

    console.log('Rendering history. Commits:', trackState.commits.length);

    if (trackState.commits.length === 0) {
      historyContainer.innerHTML = '<div style="color: #666; font-size: 0.9em; padding: 5px 0;">No snapshots yet.</div>';
      return;
    }

    // Show commits in reverse order (newest first)
    [...trackState.commits].reverse().forEach((commit) => {
      const commitElement = document.createElement('div');
      commitElement.style.borderBottom = "1px solid #eee";
      commitElement.style.padding = "10px 0";
      commitElement.style.display = "flex";
      commitElement.style.justifyContent = "space-between";
      commitElement.style.alignItems = "center";

      const infoSpan = document.createElement('span');
      infoSpan.innerHTML = `<small style="color: #666;">${commit.time.toLocaleTimeString()}</small> <strong style="margin-left: 10px;">${commit.message}</strong>`;

      const revertBtn = document.createElement('button');
      revertBtn.textContent = 'Revert';
      revertBtn.type = 'button';
      revertBtn.style.padding = '4px 12px';
      revertBtn.style.cursor = 'pointer';
      revertBtn.addEventListener('click', () => {
        console.log('Reverting to:', commit.message);
        revertCommit(commit, editor);
        renderCommitHistory();
      });

      commitElement.appendChild(infoSpan);
      commitElement.appendChild(revertBtn);
      historyContainer.appendChild(commitElement);
    });
  }
  const importBtn = document.querySelector('#import_md_btn');
  const mdFileInput = document.querySelector('#md_file_input');
  const undoBtn = document.querySelector('#undo_btn');
  const redoBtn = document.querySelector('#redo_btn');
  const boldBtn = document.querySelector('#bold_btn');
  const italicBtn = document.querySelector('#italic_btn');
  const underlineBtn = document.querySelector('#underline_btn');
  const bulletBtn = document.querySelector('#bullet_btn');
  const orderedBtn = document.querySelector('#ordered_btn');
  const headingSelect = document.querySelector('#heading_select');
  const fontSelect = document.querySelector('#font_select');
  const linkBtn = document.querySelector('#link_btn');
  const imageBtn = document.querySelector('#image_btn');
  const imageInput = document.querySelector('#image_upload')

  importBtn?.addEventListener('click', () => {
    mdFileInput?.click()
  })
  mdFileInput?.addEventListener('change', () => {
    const file = mdFileInput.files[0]
    if (!file) return
    const reader = new FileReader()
    reader.onload = (event) => {
      const markdown = event.target.result
      const md = new MarkdownIt();
      const html = md.render(markdown);
      editor.commands.setContent(html);
    }
    reader.readAsText(file)
    mdFileInput.value = ''
  })


  imageBtn?.addEventListener('click', () => {
    imageInput?.click()
  })
  imageInput?.addEventListener('change', async () => {
    const file = imageInput.files[0]
    if (!file) return
    const formData = new FormData()
    formData.append('image', file)
    try {
      const response = await fetch('/upload-image', {
        method: 'POST',
        body: formData
      })
      const data = await response.json()
      if (data.url) {
        editor.chain().focus().setImage({ src: data.url }).run()
      }
    } catch (error) {
      console.error('Error uploading image:', error)
    }
    imageInput.value = ''
  })

  linkBtn?.addEventListener('click', () => {
    const previousUrl = editor.getAttributes('link').href || ''
    const url = prompt('Enter the URL', previousUrl)
    if (url === null) return

    if (url === '') {
      editor.chain().focus().unsetLink().run()
      return
    }
    editor.chain().focus().setLink({ href: url }).run()
  })




  undoBtn?.addEventListener('click', () => {
    editor.chain().focus().undo().run()
  })

  redoBtn?.addEventListener('click', () => {
    editor.chain().focus().redo().run()
  })

  boldBtn?.addEventListener('click', () => {
    editor.chain().focus().toggleBold().run()
  })
  underlineBtn?.addEventListener('click', () => {
    editor.chain().focus().toggleUnderline().run()
  })

  italicBtn?.addEventListener('click', () => {
    editor.chain().focus().toggleItalic().run()
  })
  bulletBtn?.addEventListener('click', () => {
    editor.chain().focus().toggleBulletList().run()
  })
  orderedBtn?.addEventListener('click', () => {
    editor.chain().focus().toggleOrderedList().run()
  })
  headingSelect?.addEventListener('change', (e) => {
    const value = e.target.value
    if (value === '') {
      editor.chain().focus().setParagraph().run()
    } else {
      editor.chain().focus().toggleHeading({ level: Number(value) }).run()
    }
  })
  fontSelect?.addEventListener('change', (e) => {
    const value = e.target.value
    if (value === '') {
      editor.chain().focus().unsetFontFamily().run()
    } else {
      editor.chain().focus().setFontFamily(value).run()
    }
  })
  function bindHistoryButton(button, command) {
    if (!button) return
    const update = () => {
      button.disabled = !editor.can().chain().focus()[command]().run()
    }
    editor.on('selectionUpdate', update)
    editor.on('transaction', update)
    update()
  }
  bindHistoryButton(undoBtn, 'undo')
  bindHistoryButton(redoBtn, 'redo')

  function bindToolbarButton(button, config) {
    if (!button) return
    const update = () => {
      button.classList.toggle('active', config.isActive())
      button.disabled = !config.canRun()
    }
    editor.on('selectionUpdate', update)
    editor.on('transaction', update)
    update()
  }
  bindToolbarButton(boldBtn, {
    isActive: () => editor.isActive('bold'),
    canRun: () => editor.can().chain().focus().toggleBold().run(),
  })

  bindToolbarButton(italicBtn, {
    isActive: () => editor.isActive('italic'),
    canRun: () => editor.can().chain().focus().toggleItalic().run(),
  })
  bindToolbarButton(underlineBtn, {
    isActive: () => editor.isActive('underline'),
    canRun: () => editor.can().chain().focus().toggleUnderline().run(),
  })


  bindToolbarButton(bulletBtn, {
    isActive: () => editor.isActive('bulletList'),
    canRun: () => editor.can().chain().focus().toggleBulletList().run(),
  })


  bindToolbarButton(orderedBtn, {
    isActive: () => editor.isActive('orderedList'),
    canRun: () => editor.can().chain().focus().toggleOrderedList().run(),
  })
  bindToolbarButton(linkBtn, {
    isActive: () => editor.isActive('link'),
    canRun: () => editor.can().chain().focus().setLink({
      href: 'https://example.com'
    }).run(),
  })
  function updateHeadingSelect() {
    if (!headingSelect) return
    if (editor.isActive('heading', { level: 1 })) {
      headingSelect.value = '1'
    } else if (editor.isActive('heading', { level: 2 })) {
      headingSelect.value = '2'
    } else {
      headingSelect.value = ''
    }
  }

  function updateFontSelect() {
    if (!fontSelect) return
    const fontFamily = editor.getAttributes('textStyle').fontFamily || ''
    fontSelect.value = fontFamily
  }

  editor.on('selectionUpdate', () => {
    updateHeadingSelect()
    updateFontSelect()
  })
  editor.on('transaction', () => {
    updateHeadingSelect()
    updateFontSelect()
  })
  updateHeadingSelect()
  updateFontSelect()

  function updateToolbar() {
    boldBtn?.classList.toggle('active', editor.isActive('bold'))
    italicBtn?.classList.toggle('active', editor.isActive('italic'))
    underlineBtn?.classList.toggle('active', editor.isActive('underline'))
  }
  editor.on('selectionUpdate', updateToolbar)
  editor.on('transaction', updateToolbar)


  function bindActiveState(button, checkFn) {
    if (!button || typeof checkFn !== 'function') return
    const update = () => {
      button.classList.toggle('active', checkFn())
    }
    editor.on('selectionUpdate', update)
    editor.on('transaction', update)
  };
  bindActiveState(boldBtn, () => editor.isActive('bold'));
  bindActiveState(italicBtn, () => editor.isActive('italic'));
  bindActiveState(underlineBtn, () => editor.isActive('underline'));
  const form = editorElement.closest('form')
  const hiddenInput = document.querySelector('#content_input')
  console.log('Editor JS loaded. Hidden input found:', !!hiddenInput);
  if (hiddenInput && hiddenInput.value) {
    editor.commands.setContent(hiddenInput.value);
  }
  // Take an initial snapshot
  setTimeout(() => {
    takeSnapshot('Initial version');
  }, 100);
  form?.addEventListener('submit', () => {
    const html = editor.getHTML();
    console.log('Form submitting. HTML length:', html.length);
    if (hiddenInput) {
      hiddenInput.value = html;
    } else {
      console.error('Hidden input #content_input not found on submit!');
    }
  })
}
