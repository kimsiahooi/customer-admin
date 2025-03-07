<template>
  <div class="row justify-content-center mt-5">
    <div class="col-md-8">
      <h3>Customers</h3>
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-md-2">
              <Link
                :href="route('customers.create')"
                class="btn"
                style="background-color: #4643d3; color: white">
                <i class="fas fa-plus"></i> Create Customer
              </Link>
            </div>
            <div class="col-md-6">
              <form @submit.prevent="searchHandler">
                <div class="input-group mb-3">
                  <input
                    type="text"
                    v-model="params.search"
                    name="search"
                    class="form-control"
                    placeholder="Search anything..."
                    aria-describedby="button-addon2" />
                  <button
                    class="btn btn-outline-secondary"
                    type="submit"
                    id="button-addon2">
                    Search
                  </button>
                </div>
              </form>
            </div>
            <div class="col-md-2">
              <div class="input-group mb-3">
                <select
                  class="form-select"
                  v-model="params.order"
                  @change="sortHandler">
                  <option value="desc">Newest to Oldest</option>
                  <option value="asc">Oldest to Newest</option>
                </select>
              </div>
            </div>
            <div class="col-md-2 text-end">
              <Link :href="route('customers.trash')" class="btn btn-dark">
                <i class="fas fa-trash-alt"></i> Trash
              </Link>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table class="table table-bordered" style="border: 1px solid #dddddd">
            <thead>
              <tr>
                <th scope="col">Avatar</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Phone Number</th>
                <th scope="col">Email</th>
                <th scope="col">BAN</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="customer in customers" :key="customer.id">
                <td>
                  <img
                    style="
                      width: 40px;
                      height: 40px;
                      object-fit: cover;
                      border-radius: 50%;
                    "
                    :src="
                      customer.image
                        ? `/${customer.image}`
                        : generateAvatar(customer)
                    "
                    :alt="`${customer.first_name} ${customer.last_name}`" />
                </td>
                <td>{{ customer.first_name }}</td>
                <td>{{ customer.last_name }}</td>
                <td>{{ customer.phone }}</td>
                <td>{{ customer.email }}</td>
                <td>{{ customer.bank_account_number }}</td>
                <td>
                  <Link
                    :href="route('customers.edit', customer.id)"
                    style="color: #2c2c2c"
                    class="ms-1 me-1">
                    <i class="far fa-edit"></i>
                  </Link>
                  <Link
                    :href="route('customers.show', customer.id)"
                    style="color: #2c2c2c"
                    class="ms-1 me-1">
                    <i class="far fa-eye"></i>
                  </Link>
                  <button
                    @click="deleteCustomerHandler(customer)"
                    style="
                      color: #2c2c2c;
                      background-color: transparent;
                      padding: 0;
                      border: 0;
                    "
                    class="ms-1 me-1">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import Layout from "@/Layouts/App.vue";
import type { Customer } from "@/types/Customer";
import { Link, router } from "@inertiajs/vue3";
import { reactive, computed } from "vue";
import { pickBy } from "lodash-es";
import { useAvatar } from "@/Composables/useAvatar";

defineOptions({ layout: Layout });

const { query } = defineProps<{
  customers: Customer[];
  query: { search?: string | null; order?: string | null } | null;
}>();

const { generateAvatar } = useAvatar();

const params = reactive<{
  search: string;
  order: "asc" | "desc";
}>({
  search: query?.search ?? "",
  order: query?.order === "asc" ? "asc" : "desc",
});

const computedParams = computed(() => pickBy(params, (v) => v));

const deleteCustomerHandler = (customer: Customer) => {
  if (confirm("Are you sure you want to delete the customer?")) {
    router.delete(route("customers.destroy", customer.id));
  }
};

const fetchData = () => {
  router.get(route("customers.index"), computedParams.value, {
    preserveState: true,
    preserveScroll: true,
  });
};

const searchHandler = () => {
  fetchData();
};

const sortHandler = () => {
  fetchData();
};
</script>
