<template>
  <div>
    <div v-if="article">
      <h1 class="text-3xl font-bold">{{ article.title }}</h1>
      <p class="text-gray-600">{{ article.body }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    id: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      article: null
    }
  },
  async mounted() {
    const res = await useApiFetch(`ang/aking/url/${this.id}`)
    const data = await res.json()
    this.article = data
  }
}
</script>
