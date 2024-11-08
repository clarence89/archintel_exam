<template>
  <div
    class="w-full"
    v-if="hasPermission('administrator') || hasPermission('view_users')"
  >
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
        v-if="hasPermission('administrator') || hasPermission('add_users')"
        :loading="state.loading"
        label="Add User"
        @click="openModal = true"
      />
      <UDivider class="my-5" />
      <UModal v-model="openModal" prevent-close fullscreen>
        <UCard
          :ui="{ ring: '', divide: 'divide-y divide-gray-100 dark:divide-gray-800' }"
        >
          <template #header>
            <div class="flex items-center justify-between">
              <h3
                class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
                v-if="state.edit_data_id"
              >
                Edit User
              </h3>
              <h3
                class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
                v-else
              >
                Add Person
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
          <UForm
            :schema="schema"
            :state="state.add_data"
            class="space-y-4"
            @submit="onSubmit"
          >
            <div
              class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5"
            >
              <UFormGroup label="First Name" name="fname">
                <UInput v-model="state.add_data.fname" />
              </UFormGroup>
              <UFormGroup label="Middle Name" name="mname">
                <UInput v-model="state.add_data.mname" />
              </UFormGroup>
              <UFormGroup label="Last Name" name="lname">
                <UInput v-model="state.add_data.lname" />
              </UFormGroup>
              <UFormGroup label="Name Initial" name="nameinit">
                <UInput v-model="state.add_data.nameinit" />
              </UFormGroup>
              <UFormGroup label="Address" name="address">
                <UInput v-model="state.add_data.address" />
              </UFormGroup>
              <UFormGroup label="Birthdate" name="birthdate">
                <UInput type="date" v-model="state.add_data.birthdate" />
              </UFormGroup>
              <UFormGroup label="Gender" name="gender">
                <USelect
                  v-model="state.add_data.gender"
                  :options="[
                    { name: 'Male', value: 'Male' },
                    { name: 'Female', value: 'Female' },
                    { name: 'Other', value: 'Other' },
                  ]"
                  option-attribute="name"
                />
              </UFormGroup>
              <UFormGroup label="Affiliation" name="affiliation">
                <USelect
                  v-model="state.add_data.affiliation"
                  :options="state.affiliations"
                  option-attribute="name"
                />
              </UFormGroup>

              <div>
                <UFormGroup label="Add User Login Credentials" name="user_credentials">
                  <UCheckbox v-model="state.add_data.user_credentials" />
                </UFormGroup>
              </div>
            </div>
            <div
              class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5"
            >
              <UFormGroup
                v-if="state.add_data.user_credentials"
                label="Username"
                name="username"
              >
                <UInput v-model="state.add_data.username" />
              </UFormGroup>
              <UFormGroup
                v-if="state.add_data.user_credentials"
                label="Password"
                name="password"
              >
                <UInput type="password" v-model="state.add_data.password" />
              </UFormGroup>
              <UFormGroup v-if="state.add_data.user_credentials" label="User Type" name="role">
                <USelect
                  v-model="state.add_data.role"
                  :options="state.roles"
                  option-attribute="name"
                />
              </UFormGroup>
              <UFormGroup
                v-if="state.add_data.user_credentials && state.edit_data_id"
                label="Status"
                name="status"
              >
                <USelect
                  v-model="state.add_data.status"
                  :options="[
                    { name: 'Active', value: 0 },
                    { name: 'Inactive', value: 1 },
                  ]"
                  option-attribute="name"
                />
              </UFormGroup>
            </div>
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
          <UInput v-model="q" placeholder="Filter User Name..." />
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
          <template #status_name-data="{ row }">
            <p>
              {{ row.status == 0 ? "Active" : "Inactive" }}
            </p>
          </template>
          <template #actions-data="{ row }">
            <UDropdown
              :items="items(row)"
              v-if="hasPermission('administrator') || hasPermission('edit_users')"
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
    <UAlert color="red" variant="solid" title="Permission Denied" class="mb-5" />
  </div>
</template>
<script setup lang="ts">
import { object, string, boolean, type InferType } from "yup";
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

const User = {
  fname: "",
  mname: "",
  lname: "",
  address: "",
  nameinit: "",
  affiliation: "",
  gender: "",
  birthdate: "",
  created_at: "",
  directory_id: "",
  username: "",
  password: "",
  role: "",
  status: "",
  user_credentials: false,
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
  add_data: User,
  error: "",
  success: "",
  roles: [],
  affiliations: [],
});
async function getAllPermissions() {
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
    });
}
async function getAllRoles() {
  await useApiFetch("/api/role/getAll.php", {
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
        state.roles = result.map((role: any) => ({
          name: role.role_name,

          value: role.id,
        }));
      }
    });
}
async function getAllAffiliation() {
  await useApiFetch("/api/affiliation/getAll.php", {
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
        state.affiliations = result.map((role: any) => ({
          name: role.affiliation_name,
          value: role.id,
        }));
      }
    });
}
const columns = [
  {
    key: "fname",
    label: "First Name",
  },
  {
    key: "mname",
    label: "Middle Name",
  },
  {
    key: "lname",
    label: "Last Name",
  },
  {
    key: "address",
    label: "Address",
  },
  {
    key: "status_name",
    label: "Status",
  },
  {
    key: "actions",
  },
];
async function onSubmitPermission() {
  state.loading = true;
  let data = new FormData();
  data.append("user_id", state.permission_data_id);
  data.append("permissions", JSON.stringify(state.permissions.filter((permission) => permission.selected).map((permission) => permission.id)));
  await useApiFetch("/api/user_directory/updatePermissions.php", {
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
    });
  await getPermissions();
}
function editData(edit_data: any) {
  state.edit_data_id = edit_data.id;
  var data = edit_data;
  state.add_data = data;
  if (edit_data.username != "") {
    state.add_data.user_credentials = true;
  }
  openModal.value = true;
}
async function getPermissions() {
  state.loading = true;
  let data = new FormData();
  data.append("user_id", state.permission_data_id);
  await useApiFetch("/api/user_directory/getPermissions.php", {
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
        });
      }
    });
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
    const response = await useApiFetch("/api/user_directory/getAll.php/");
    state.data = await response.json();
    state.loading = false;
  } catch (error) {
    console.error("Error fetching data:", error);
    state.data = [];
  }
}
onMounted(async () => {
  await getData();
  getAllRoles();
  getAllPermissions();
  getAllAffiliation();
});
function tryLoading(id: string) {
  state.buttonLoading = { ...state.buttonLoading, [id]: true };
}
const schema = object({
  user_credentials: boolean(),
  fname: string().required("Required").min(3, "Must be at least 3 characters"),
  mname: string().required("Required").min(2, "Must be at least 2 characters"),
  lname: string().required("Required").min(2, "Must be at least 2 characters"),
  nameinit: string().required("Required").min(2, "Must be at least 2 characters"),
  address: string().required("Required").min(10, "Must be at least 10 characters"),
  birthdate: string().required("Required"),
  gender: string().required("Required"),
  affiliation: string().required("Required"),
  username: string().when("user_credentials", {
    is: true,
    then: (schema) => schema.required("Required").min(5, "Must be at least 5 characters"),
    otherwise: (schema) => schema.notRequired(),
  }),
  role: string().when("user_credentials", {
    is: true,
    then: (schema) => schema.required("Required"),
    otherwise: (schema) => schema.notRequired(),
  }),
  password: string().when("user_credentials", {
    is: true,
    then: (schema) => schema.test('len', 'Password must be at least 6 characters', (val) => {
      // Allow empty string or null, but enforce min length if a value is present
      return val == null || val === '' || val.length >= 6;
    }),
    otherwise: (schema) => schema.notRequired(),
  }),
});
async function onSubmit(event: FormSubmitEvent<Schema>) {
  state.error = "";
  state.success = "";
  state.loading = true;
  const formdata = new FormData();
  Object.keys(state.add_data).forEach((key) => formdata.append(key, state.add_data[key as keyof typeof state.add_data] as string));
  let url = "/api/user_directory/createData.php/";
  if (state.edit_data_id) {
    formdata.append("edit_data_id", state.edit_data_id);
    url = "/api/user_directory/updateData.php/";
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
  resetData();
  openModal.value = false;
  state.loading = false;
  getData();
}
function resetData() {
  state.edit_data_id = null;
  state.data_permissions = [];
  state.add_data = User;
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
watch(
  () => state.add_data.user_credentials,
  (newValue) => {
    if (!newValue) {
      state.add_data.username = "";
      state.add_data.password = "";
      state.add_data.role = "";
    }
  }
);
</script>
