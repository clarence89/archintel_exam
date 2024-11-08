<template>
  <div class="w-full" v-if="hasPermission('administrator') || hasPermission('view_role')">
    <UAlert
      v-if="state.success"
      color="primary"
      variant="solid"
      :title="state.success"
      class="mb-5"
      style="z-index: -999"
    />
    <UAlert
      v-if="state.error"
      color="red"
      variant="solid"
      :title="state.error"
      class="mb-5"
      style="z-index: -999"
    />
    <div>
      <UButton
        v-if="hasPermission('administrator') || hasPermission('add_role')"
        :loading="state.loading"
        label="Add New Role"
        @click="openModal = true"
      />
      <UDivider class="my-5" />
      <UModal v-model="openModal" prevent-close>
        <UCard
          :ui="{ ring: '', divide: 'divide-y divide-gray-100 dark:divide-gray-800' }"
        >
          <template #header>
            <div class="flex items-center justify-between">
              <h3
                class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
                v-if="state.edit_data_id"
              >
                Edit Role
              </h3>
              <h3
                class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
                v-else
              >
                Add New Role
              </h3>
              <UButton
                color="gray"
                variant="ghost"
                icon="i-heroicons-x-mark-20-solid"
                class="-my-1"
                @click="openModal = false"
              />
            </div>
          </template>
          <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
            <UFormGroup label="Role Name" name="add_data_name">
              <UInput v-model="state.add_data_name" />
            </UFormGroup>
            <UButton block type="submit" :loading="state.loading"> Submit </UButton>
          </UForm>
        </UCard>
      </UModal>
    </div>
    <div
      class="grid grid-cols-1 gap-4"
      :class="{ 'grid-cols-2': state.permission_data_id !== null }"
    >
      <div>
        <div class="flex px-3 py-3.5 border-b border-gray-200 dark:border-gray-700">
          <UInput v-model="q" placeholder="Filter Role Name..." />
        </div>
        <UTable
          :loading="state.loading"
          :rows="rows"
          :columns="columns"
          :empty-state="{ icon: 'i-heroicons-circle-stack-20-solid', label: 'No items.' }"
          :loading-state="{
            icon: 'i-heroicons-arrow-path-20-solid',
            label: 'Loading...',
          }"
        >
          <template #actions-data="{ row }">
            <UDropdown
              :items="items(row)"
              v-if="hasPermission('administrator') || hasPermission('edit_role')"
            >
              <UButton
                :loading="state.buttonLoading[`${row.id}`] || state.loading"
                color="gray"
                variant="ghost"
                icon="i-heroicons-ellipsis-horizontal-20-solid"
              />
            </UDropdown>
          </template>
        </UTable>
        <div
          class="flex justify-end px-3 py-3.5 border-t border-gray-200 dark:border-gray-700"
        >
          <UPagination
            v-model="page"
            :page-count="pageCount"
            :total="state.data.length"
          />
        </div>
      </div>
      <div v-if="state.permission_data_id !== null">
        <p class="text-lg font-semibold mb-4">Select Permissions</p>
        <form @submit.prevent="onSubmitPermission" class="space-y-2">
          <div class="grid grid-cols-3 gap-4 mb-10">
            <div v-for="permission in state.permissions" class="flex items-center">
              <UCheckbox v-model="permission.selected" :value="permission.id" />
              <span class="ml-2">{{ permission.permission_name }}</span>
            </div>
          </div>
          <UButton block type="submit" :loading="state.loading" class="mt-2"
            >Update Permissions</UButton
          >
          <UButton
            block
            to="#"
            @click="resetData()"
            :loading="state.loading"
            class="mt-2 bg-red-500"
            >Cancel</UButton
          >
        </form>
      </div>
    </div>
  </div>
  <div v-else>
    <UAlert
      color="red"
      variant="solid"
      title="Permission Denied"
      class="mb-5"
      style="z-index: -999"
    />
  </div>
</template>
<script setup lang="ts">
import { object, string, type InferType } from "yup";
import type { FormSubmitEvent } from "#ui/types";
type Schema = InferType<typeof schema>;
type ButtonLoadingState = {
    [key: string]: boolean;
};
type Permission = {
  id: string;
  permission_name: string;
  selected: boolean;
};
var openModal = ref(false);
const state = reactive({
    data: [],
    loading: false,
    buttonLoading: {} as ButtonLoadingState,
    edit_data_id: null,
    permission_data_id: null as any,
    data_permissions: [],
    permissions: [] as Permission[],
    add_data_name: "",
    error: "",
    success: "",
});
async function getAllPermissions() {
    state.loading = true;
    await useApiFetch("/api/permission/getAll.php", {
        method: "POST",
    })
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
                state.permissions = result;
            }
        })
    state.loading = false;
}
const columns = [
    {
        key: "role_name",
        label: "Role",
    },
    {
        key: "actions",
    },
];
async function onSubmitPermission() {
    state.loading = true;
    let data = new FormData();
    data.append("role_id", state.permission_data_id);
    data.append("permissions", JSON.stringify(state.permissions.filter((permission) => permission.selected).map((permission) => permission.id)));
    await useApiFetch("/api/role/updatePermissions.php", {
        method: "POST",
        body: data,
    })
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
            state.loading = false;
        })
    await getPermissions();
}
function editData(edit_data: any) {
    state.edit_data_id = edit_data.id;
    state.add_data_name = edit_data.role_name;
    openModal.value = true;
}
async function getPermissions() {
    state.loading = true;
    await getAllPermissions();
    let data = new FormData();
    data.append("role_id", state.permission_data_id);
    await useApiFetch("/api/role/getPermissions.php", {
        method: "POST",
        body: data,
    })
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
                state.data_permissions = result;
                result.forEach((permission: any) => {
                    state.permissions.forEach((permission2) => {
                        if (permission2.id == permission.id) {
                            permission2.selected = true;
                        }
                    });
                })
            }
        })
    state.loading = false;
}
function editPermissions(edit_data: any) {
    state.permission_data_id = edit_data.id;
    getPermissions();
}

const items = (row: { id: any }) => [
    [
        {
            label: "Edit",
            icon: "i-heroicons-pencil-square-20-solid",
            click: () => editData(row),
        },
        {
            label: "Edit Permissions",
            icon: "i-heroicons-pencil-square-20-solid",
            click: () => editPermissions(row),
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
        const response = await useApiFetch("/api/role/getAll.php/");
        state.data = await response.json();
        state.loading = false;
    } catch (error) {
        console.error("Error fetching data:", error);
        state.data = [];
    }
}
onMounted(async () => {
    await getData();
    await getAllPermissions();
});
function tryLoading(id: string) {
    state.buttonLoading = { ...state.buttonLoading, [id]: true };
}
const schema = object({
    add_data_name: string().required("Required").min(5, "Must be at least 5 characters"),
});
async function onSubmit(event: FormSubmitEvent<Schema>) {
    state.error = "";
    state.success = "";
    state.loading = true;
    const formdata = new FormData();
    formdata.append("role_name", state.add_data_name);
    let url = "/api/Role/createData.php/";
    if (state.edit_data_id) {
        formdata.append("role_id", state.edit_data_id);
        url = "/api/Role/updateData.php/";
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
                state.success = "Success";
            }
        })
        .catch((error) => (state.error = String(error)));
    resetData();
    openModal.value = false;
    state.loading = false;
    getData();
}
function resetData() {
    state.edit_data_id = null;
    state.data_permissions = [];
    state.add_data_name = "";
    state.permission_data_id = null;
}
watch(
    () => openModal.value,
    (newValue) => {
        if (!newValue) {
            resetData();
        }
    }
);
</script>
