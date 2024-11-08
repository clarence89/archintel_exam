<template>
  <div class="w-full" v-if="hasPermission('administrator') || hasPermission('view_companies')">
    <UAlert v-if="state.success" color="primary" variant="solid" :title="state.success" class="mb-5" style="z-index: -999" />
    <UAlert v-if="state.error" color="red" variant="solid" :title="state.error" class="mb-5" style="z-index: -999" />
    <div>
      <UButton v-if="hasPermission('administrator') || hasPermission('add_companies')" :loading="state.loading" label="Add New Company" @click="openModal = true" />
      <UDivider class="my-5" />
      <UModal v-model="openModal" prevent-close>
        <UCard :ui="{ ring: '', divide: 'divide-y divide-gray-100 dark:divide-gray-800' }">
          <template #header>
            <div class="flex items-center justify-between">
              <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white" v-if="state.edit_data_id">Edit Company</h3>
              <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white" v-else>Add New Company</h3>
              <UButton color="gray" variant="ghost" icon="i-heroicons-x-mark-20-solid" class="-my-1" @click="openModal = false" />
            </div>
          </template>
          <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
            <UFormGroup label="Company Name" name="add_data_name">
              <UInput v-model="state.add_data_name" />
            </UFormGroup>
            <UFormGroup label="Company Logo" name="add_logo">
              <UInput v-model="state.add_logo" />
            </UFormGroup>
            <UFormGroup label="Status" name="add_status">
              <USelect
                v-model="state.add_status"
                :options="[
                  { name: 'Active', value: 0 },
                  { name: 'Inactive', value: 1 },
                ]"
                option-attribute="name"
              />
            </UFormGroup>
            <UButton block type="submit" :loading="state.loading"> Submit </UButton>
          </UForm>
        </UCard>
      </UModal>
    </div>
    <div class="flex px-3 py-3.5 border-b border-gray-200 dark:border-gray-700">
      <UInput v-model="q" placeholder="Filter Company Name..." />
    </div>
    <UTable :loading="state.loading" :rows="rows" :columns="columns" :empty-state="{ icon: 'i-heroicons-circle-stack-20-solid', label: 'No items.' }" :loading-state="{ icon: 'i-heroicons-arrow-path-20-solid', label: 'Loading...' }">
      <template #logo-data="{ row }">
        <img :src="row.logo" alt="Company Logo" class="w-10 h-10 object-cover" />
      </template>
      <template #status-data="{ row }">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-800" v-if="row.status == 0">Active</span>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-red-100 text-red-800 dark:bg-red-200 dark:text-red-800" v-else>Inactive</span>
      </template>
      <template #actions-data="{ row }">
        <UDropdown :items="items(row)" v-if="hasPermission('administrator') || hasPermission('edit_companies')">
          <UButton :loading="state.buttonLoading[`${row.id}`] || state.loading" color="gray" variant="ghost" icon="i-heroicons-ellipsis-horizontal-20-solid" />
        </UDropdown>
      </template>
    </UTable>
    <div class="flex justify-end px-3 py-3.5 border-t border-gray-200 dark:border-gray-700">
      <UPagination v-model="page" :page-count="pageCount" :total="state.data.length" />
    </div>
  </div>
  <div v-else>
    <UAlert color="red" variant="solid" title="Permission Denied" class="mb-5" style="z-index: -999" />
  </div>
</template>
<script setup lang="ts">
  import { object, string, type InferType } from "yup";
  import type { FormSubmitEvent } from "#ui/types";
  type Schema = InferType<typeof schema>;
  type ButtonLoadingState = {
    [key: string]: boolean;
  };
  var openModal = ref(false);
  const state = reactive({
    data: [],
    loading: false,
    buttonLoading: {} as ButtonLoadingState,
    edit_data_id: null,
    add_data_name: "",
    add_logo: "",
    add_status: "",
    error: "",
    success: "",
  });
  const columns = [
    {
      key: "company_name",
      label: "Company",
    },
    {
      key: "logo",
      label: "Company Logo",
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
    state.edit_data_id = edit_data.id;
    state.add_data_name = edit_data.company_name;
    state.add_logo = edit_data.logo;
    state.add_status = edit_data.status;
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

  const rows = computed(() => {
    return filteredRows.value.slice((page.value - 1) * pageCount, page.value * pageCount);
  });

  async function getData() {
    state.loading = true;
    try {
      const response = await useApiFetch("/api/company/getAll.php/");
      state.data = await response.json();
      state.loading = false;
    } catch (error) {
      console.error("Error fetching data:", error);
      state.data = [];
    }
  }
  onMounted(async () => {
    await getData();
  });
  function tryLoading(id: string) {
    state.buttonLoading = { ...state.buttonLoading, [id]: true };
  }
  const schema = object({
    add_data_name: string().required("Required").min(5, "Must be at least 5 characters"),
    add_logo: string()
      .required("Required")
      .test("url", "Must be a valid URL or Data URI", (value) => {
        if (value.startsWith("data:image/")) {
          return true;
        }
        return /^https?:\/\//.test(value);
      }),
    add_status: string().required("Required").matches(/(0|1)/, "Must be Active or Inactive"),
  });
  async function onSubmit(event: FormSubmitEvent<Schema>) {
    state.error = "";
    state.success = "";
    state.loading = true;
    const formdata = new FormData();
    formdata.append("company_name", state.add_data_name);
    formdata.append("logo", state.add_logo);
    formdata.append("status", state.add_status);
    let url = "/api/company/createData.php/";
    if (state.edit_data_id) {
      formdata.append("id", state.edit_data_id);
      url = "/api/company/updateData.php/";
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
    getData();
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
  function resetFields() {
    state.edit_data_id = null;
    state.add_data_name = "";
    state.add_logo = "";
    state.add_status = "";
  }
</script>
