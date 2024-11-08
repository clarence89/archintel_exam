<template>
  <div>
    <div v-if="editor">
      <UButton class="bg-gray-200 text-black border border-1 m-1"
        @click="editor.chain().focus().toggleBold().run()"
        :disabled="!editor.can().chain().focus().toggleBold().run()"
        :class="{ 'is-active': editor.isActive('bold') }"
      >
        B
      </UButton>
<UButton class="bg-gray-200 text-black border border-1 m-1"
        @click="editor.chain().focus().toggleItalic().run()"
        :disabled="!editor.can().chain().focus().toggleItalic().run()"
        :class="{ 'is-active': editor.isActive('italic') }"
      >
        I
      </UButton>
 <UButton class="bg-gray-200 text-black border border-1 m-1"
        @click="editor.chain().focus().toggleStrike().run()"
        :disabled="!editor.can().chain().focus().toggleStrike().run()"
        :class="{ 'is-active': editor.isActive('strike') }"
      >
        Strike
      </UButton>
<UButton class="bg-gray-200 text-black border border-1 m-1"
        @click="editor.chain().focus().toggleCode().run()"
        :disabled="!editor.can().chain().focus().toggleCode().run()"
        :class="{ 'is-active': editor.isActive('code') }"
      >
        Code
      </UButton>
     <UButton class="bg-gray-200 text-black border border-1 m-1"
        @click="editor.chain().focus().setParagraph().run()"
        :class="{ 'is-active': editor.isActive('paragraph') }"
      >
        P
      </UButton>
     <UButton class="bg-gray-200 text-black border border-1 m-1"
        @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
        :class="{ 'is-active': editor.isActive('heading', { level: 1 }) }"
      >
        H1
      </UButton>
      <UButton class="bg-gray-200 text-black border border-1 m-1"
        @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
        :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }"
      >
        H2
      </UButton>
      <UButton class="bg-gray-200 text-black border border-1 m-1"
        @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
        :class="{ 'is-active': editor.isActive('heading', { level: 3 }) }"
      >
        H3
      </UButton>
      <UButton class="bg-gray-200 text-black border border-1 m-1"
        @click="editor.chain().focus().toggleHeading({ level: 4 }).run()"
        :class="{ 'is-active': editor.isActive('heading', { level: 4 }) }"
      >
        H4
      </UButton>
      <UButton class="bg-gray-200 text-black border border-1 m-1"
        @click="editor.chain().focus().toggleHeading({ level: 5 }).run()"
        :class="{ 'is-active': editor.isActive('heading', { level: 5 }) }"
      >
        H5
      </UButton>
      <UButton class="bg-gray-200 text-black border border-1 m-1"
        @click="editor.chain().focus().toggleHeading({ level: 6 }).run()"
        :class="{ 'is-active': editor.isActive('heading', { level: 6 }) }"
      >
        H6
      </UButton>
      <UButton class="bg-gray-200 text-black border border-1 m-1"
        @click="editor.chain().focus().toggleBulletList().run()"
        :class="{ 'is-active': editor.isActive('bulletList') }"
      >
        Bullet list
      </UButton>
      <UButton class="bg-gray-200 text-black border border-1 m-1"
        @click="editor.chain().focus().toggleOrderedList().run()"
        :class="{ 'is-active': editor.isActive('orderedList') }"
      >
        Ordered list
      </UButton>
      <UButton class="bg-gray-200 text-black border border-1 m-1"
        @click="editor.chain().focus().toggleCodeBlock().run()"
        :class="{ 'is-active': editor.isActive('codeBlock') }"
      >
        Code block
      </UButton>
      <UButton class="bg-gray-200 text-black border border-1 m-1"
        @click="editor.chain().focus().toggleBlockquote().run()"
        :class="{ 'is-active': editor.isActive('blockquote') }"
      >
        Blockquote
      </UButton>
      <UButton class="bg-gray-200 text-black border border-1 m-1" @click="editor.chain().focus().setHorizontalRule().run()">
        Horizontal rule
      </UButton>
      <UButton class="bg-gray-200 text-black border border-1 m-1" @click="editor.chain().focus().setHardBreak().run()">
        Hard break
      </UButton>
    </div>
    <TiptapEditorContent :editor="editor" />
  </div>
</template>

<script setup>
import { ref, onBeforeUnmount, unref, watch, defineProps } from 'vue'


// Get `modelValue` prop from parent for v-model support
const props = defineProps({
  modelValue: String,
})

const emit = defineEmits(['update:modelValue'])
// Initialize editor with content from `modelValue`
const editor = useEditor({
      editorProps: {
    attributes: {
      class: 'prose prose-sm sm:prose lg:prose-lg xl:prose-2xl mx-auto focus:outline-none border border-gray-300 rounded-md h-64 overflow-y-scroll',
    },
  },
  content: props.modelValue || "",
  extensions: [TiptapStarterKit],
  onUpdate({ editor }) {
    // Emit changes to `modelValue` whenever the editor content is updated
    emit('update:modelValue', editor.getHTML())
  },
})

// Watch `modelValue` prop to update the editor content if it changes externally
// watch(() => props.modelValue, (newValue) => {
//   if (newValue !== editor.getHTML) {
//     editor.commands.setContent(newValue)
//   }
// })

onBeforeUnmount(() => {
  unref(editor).destroy()
})
</script>
