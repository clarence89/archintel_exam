<template>
  <UAlert v-if="state.success" color="primary" variant="solid" :title="state.success" class="mb-5" style="z-index: -999" />
  <UAlert v-if="state.error" color="red" variant="solid" :title="state.error" class="mb-5" style="z-index: -999" />
  <template v-if="state.companies.length > 0">
    Note: Select a company to view articles.
    <div class="relative w-full mx-auto">
      <!-- Carousel Container -->
      <div class="flex transition-transform duration-300 ease-in-out" :style="{ transform: `translateX(-${activeIndex * 100}%)` }" ref="carouselContainer">
        <!-- Carousel Items (Buttons) -->
        <div v-for="(company, index) in state.companies" :key="index" class="flex-shrink-0 sm:w-1/2 md:w-1/6 lg:w-1/12 px-2 py-2 overflow-hidden">
          <UTooltip style="z-index: 999" :text="company.company_name">
            <img :src="company.logo" @click="selectCompany(company)" class="p-4 text-white rounded-lg hover:bg-blue-600 transition w-28 h-28" :class="{ 'bg-blue-600': company.id == state.add_data_company.id }" />
          </UTooltip>
        </div>
      </div>

      <!-- Controls -->
      <button class="absolute top-1/2 -translate-y-1/2 left-2 bg-gray-800 text-white px-2 py-1 rounded" @click="prevSlide" :disabled="activeIndex === 0"><</button>
      <button class="absolute top-1/2 -translate-y-1/2 right-2 bg-gray-800 text-white px-2 py-1 rounded" @click="nextSlide" :disabled="activeIndex === maxIndex">></button>
    </div>
    <UDivider class="mb-10" />
  </template>

  <div v-if="hasPermission('administrator') || hasPermission('view_articles')">
    <div v-if="Object.keys(state.add_data_company).length !== 0">
      <div>
        <div class="text-right">
          <UButton v-if="hasPermission('administrator') || hasPermission('add_articles')" :loading="state.loading" label="Add New Article" @click="openModal = true" />
          <p class="text-lg font-semibold my-4">
            Selected Company: <span class="bg-gray-200 rounded px-2 py-1">{{ state.add_data_company.company_name }}</span>
          </p>
        </div>
        <UDivider class="my-5" />
        <UModal v-model="openModal" prevent-close style="z-index: 999">
          <UCard :ui="{ ring: '', divide: 'divide-y divide-gray-100 dark:divide-gray-800' }">
            <template #header>
              <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white" v-if="state.edit_data_id">Edit Article</h3>
                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white" v-else>Add New Article</h3>
                <UButton color="gray" variant="ghost" icon="i-heroicons-x-mark-20-solid" class="-my-1" @click="openModal = false" />
              </div>
            </template>
            <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
              <UFormGroup label="Article Image Url" name="add_data_image">
                <UInput v-model="state.add_data_image" />
              </UFormGroup>
              <UFormGroup label="Article Title" name="add_data_title">
                <UInput v-model="state.add_data_title" />
              </UFormGroup>
              <UFormGroup label="Article link" name="add_data_link">
                <UInput v-model="state.add_data_link" />
              </UFormGroup>
              <UFormGroup label="Article date" name="add_data_date">
                <UInput type="date" v-model="state.add_data_date" />
              </UFormGroup>
              <UFormGroup label="Article Content" name="add_data_content">
                <T-editor v-model="state.add_data_content" />
              </UFormGroup>
              <UButton block type="submit" icon="i-heroicons-check-circle" :loading="state.loading" v-if="!state.edit_data_id && (hasPermission('add_articles') || hasPermission('administrator'))"> Create Article </UButton>
              <UButton block type="submit" icon="i-heroicons-check-circle" @click="state.edit_type = 'update'" :loading="state.loading" v-if="state.edit_data_id && (hasPermission('edit_articles') || hasPermission('administrator'))"> Save </UButton>
              <UButton block type="submit" icon="i-heroicons-arrow-down-tray" :loading="state.loading" v-if="state.edit_data_id && (hasPermission('edit_published_articles') || hasPermission('administrator'))" @click="state.edit_type = 'publish'"> Publish </UButton>
            </UForm>
          </UCard>
        </UModal>
      </div>
      <div class="flex px-3 py-3.5 border-b border-gray-200 dark:border-gray-700" v-if="rows.length > 0">
        <UInput v-model="q" placeholder="Filter Article Name..." />
      </div>
      <div class="text-center text-gray-600 dark:text-gray-400" v-else>No data</div>
      <UTable :loading="state.loading" :rows="rows" :columns="columns" :empty-state="{ icon: 'i-heroicons-circle-stack-20-solid', label: 'No items.' }" :loading-state="{ icon: 'i-heroicons-arrow-path-20-solid', label: 'Loading...' }">
        <template #image-data="{ row }">
          <img class="h-32 w-32 object-cover rounded-full" :src="row.image" />
        </template>
        <template #link-data="{ row }">
          <a class="text-blue-600  dark:text-blue-400 hover:underline" :href="row.link" target="_blank" rel="noopener noreferrer">{{ row.link }}</a>
        </template>
        <template #status-data="{ row }">
          <span v-if="row.status == 0" class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-200 dark:text-yellow-800">For Edit</span>
          <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-800">Published</span>
        </template>
        <template #actions-data="{ row }">
          <UDropdown :items="items(row)" v-if="(hasPermission('administrator') || hasPermission('edit_articles') && row.status == 0) || (hasPermission('edit_published_articles') && row.status == 1)">
            <UButton :loading="state.buttonLoading[`${row.id}`] || state.loading" color="gray" variant="ghost" icon="i-heroicons-ellipsis-horizontal-20-solid" />
          </UDropdown>
        </template>
      </UTable>
      <div class="flex justify-end px-3 py-3.5 border-t border-gray-200 dark:border-gray-700">
        <UPagination v-model="page" :page-count="pageCount" :total="state.data.length" />
      </div>
    </div>
  </div>
  <div v-else>
    <UAlert color="red" variant="solid" title="Permission Denied" class="mb-5" style="z-index: -999" />
  </div>
</template>
<script setup lang="ts">
  import { date, object, string, type InferType } from "yup";
  import type { FormSubmitEvent } from "#ui/types";
  type Schema = InferType<typeof schema>;
  type ButtonLoadingState = {
    [key: string]: boolean;
  };
  type Company = {
    company_name: string;
    id: string;
    logo: string;
    status: string;
  };
  type Article = {
    id: string;
    title: string;
    image: string;
    link: string;
    date: string;
    content: string;
    company_id: string;
    status: number;
    writer_fullname: string;
    editor_fullname: string;
  };
  const user = useCookie("user").value;
  var openModal = ref(false);
  const state = reactive({
    companies: [] as Company[],
    data: [],
    loading: false,
    buttonLoading: {} as ButtonLoadingState,
    edit_data_id: null,
    edit_type: "",
    add_data_image: "",
    add_data_title: "",
    add_data_link: "",
    add_data_date: "",
    add_data_content: "",
    add_data_company: {} as Company,
    error: "",
    success: "",
  });
  const columns = [
    {
      key: "image",
      label: "Image",
    },
    {
      key: "title",
      label: "Title",
    },
    {
      key: "link",
      label: "Link",
    },
    {
      key: "writer_fullname",
      label: "Writer",
    },
    {
      key: "editor_fullname",
      label: "Editor",
    },
    {
      key: "status",
      label: "Status",
    },
    {
      key: "actions",
    },
  ];
function editData(edit_data: any) {
    console.log(edit_data)
    state.edit_data_id = edit_data.id;
    state.add_data_image = edit_data.image;
    state.add_data_title = edit_data.title;
    state.add_data_link = edit_data.link;
    state.add_data_date = edit_data.date;
    state.add_data_content = edit_data.content;
    openModal.value = true;
  }

  const items = (row: { id: any }) => [
    [
      {
        label: "Edit",
        icon: "i-heroicons-pencil-square-20-solid",
        click: () => editData(row),
      },
    ],
  ];
  const q = ref("");

  const filteredRows = computed(() => {
    if (!q.value) {
      return state.data;
    }
    return state.data.filter((person) => {
      return Object.values(person).some((value) => {
        return String(value).toLowerCase().includes(q.value.toLowerCase());
      });
    });
  });
  const page = ref(1);
  const pageCount = 5;

  const rows = computed<Article[]>(() => {
    return filteredRows.value.slice((page.value - 1) * pageCount, page.value * pageCount);
  });

  async function getData() {
    state.loading = true;
    try {
      const response_company = await useApiFetch("/api/company/getAll.php/");
      state.companies = await response_company.json();
      state.loading = false;
    } catch (error) {
      console.error("Error fetching data:", error);
      state.data = [];
    }
  }
  onMounted(async () => {
    await getData();
  });
  async function getArticles() {
    var fetchdata_form = new FormData();
    fetchdata_form.append("company_id", state.add_data_company.id);
    try {
      const response = await useApiFetch("/api/article/getAll.php/", {
        method: "POST",
        body: fetchdata_form,
      });
      state.data = await response.json();
      state.loading = false;
    } catch (error) {
      console.error("Error fetching data:", error);
      state.data = [];
    }
  }
  function tryLoading(id: string) {
    state.buttonLoading = { ...state.buttonLoading, [id]: true };
  }
  const schema = object({
    add_data_image: string()
      .required("Required")
      .test("url", "Must be a valid URL or Data URI", (value) => {
        if (value.startsWith("data:image/")) {
          return true;
        }
        return /^https?:\/\//.test(value);
      }),
    add_data_title: string().required("Required").min(5, "Must be at least 5 characters"),
    add_data_link: string().required("Required").url("Must be a valid URL"),
    add_data_date: date()
      .default(() => new Date())
      .required("Required")
      .typeError("Must be a valid date"),
    add_data_content: string().required("Required").min(5, "Must be at least 5 characters"),
  });
  async function onSubmit(event: FormSubmitEvent<Schema>) {
    state.error = "";
    state.success = "";
    state.loading = true;
    const formdata = new FormData();
    formdata.append("image", state.add_data_image);
    formdata.append("title", state.add_data_title);
    formdata.append("link", state.add_data_link);
    formdata.append("date", state.add_data_date);
    formdata.append("content", state.add_data_content);
    formdata.append("company_id", state.add_data_company.id);
    let url = "/api/article/createData.php/";
    if (state.edit_data_id) {
      formdata.append("id", state.edit_data_id);
      if (state.edit_type == "publish") {
        url = "/api/article/publishData.php/";
      } else {
        url = "/api/article/updateData.php/";
      }
    }
    const requestOptions = {
      method: "POST",
      body: formdata,
    };
    await useApiFetch(url, requestOptions)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then((result) => {
        if (result.error) {
          state.error = result.message;
        } else {
          state.success = result.message;
        }
      })
      .catch((error) => (state.error = String(error)));
    resetFields();
    openModal.value = false;
    state.loading = false;
    getArticles();
  }
  watch(
    () => openModal.value,
    (newValue) => {
      console.log("openModal", newValue);
      if (!newValue) {
        resetFields();
        console.log("openModal", newValue);
      }
    }
  );
  watch(
    () => state.add_data_company,
    (newValue) => {
      getArticles();
    }
  );

  function resetFields() {
    state.edit_data_id = null;
    state.edit_type = "";
    state.add_data_image = "";
    state.add_data_title = "";
    state.add_data_link = "";
    state.add_data_date = "";
    state.add_data_content = "";
  }
  const activeIndex = ref(0);

  const maxIndex = ref(0);
  const handleResize = () => {
    if (process.browser) {
      const width = window.innerWidth;
      let divisor;

      if (width >= 1024) {
        divisor = 6;
      } else if (width >= 768) {
        divisor = 3;
      } else {
        divisor = 1;
      }

      maxIndex.value = Math.max(0, Math.ceil(state.companies.length / divisor) - 1);
    }
  };

  if (process.browser) {
    window.addEventListener("resize", handleResize);
  }

  onMounted(() => {
    handleResize();
  });

  // Function to go to the next "slide" of 5 buttons
  function nextSlide() {
    if (activeIndex.value < maxIndex.value) {
      activeIndex.value++;
    }
  }

  // Function to go to the previous "slide" of 5 buttons
  function prevSlide() {
    if (activeIndex.value > 0) {
      activeIndex.value--;
    }
  }

  // Button click handler (can be customized further)
  function selectCompany(company: any) {
    state.add_data_company = company;
  }
</script>

<style scoped>
  /* Optional styling for smoother animations */
  .carousel-item {
    transition: transform 0.3s ease-in-out;
  }
</style>
