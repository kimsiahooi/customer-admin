<template>
  <div class="row justify-content-center mt-5">
    <div class="col-md-8">
      <h3>Customers</h3>
      <template v-if="page.props.errors">
        <template v-for="error in page.props.errors" :key="error">
          <div class="alert alert-danger">{{ error }}</div>
        </template>
      </template>
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-md-2">
              <Link
                :href="route('customers.index')"
                class="btn"
                style="background-color: #4643d3; color: white">
                <i class="fas fa-chevron-left"></i> Back
              </Link>
            </div>
          </div>
        </div>
        <div class="card-body">
          <form @submit.prevent="formHandler">
            <div class="row">
              <div class="col-md-12 mb-3">
                <img
                  style="width: 100px; height: 100px; object-fit: cover"
                  :src="
                    customer.image
                      ? `/${customer.image}`
                      : generateAvatar(customer)
                  "
                  :alt="`${form.first_name} ${form.last_name}`" />
                <div class="form-group">
                  <label for="">Image</label>
                  <input
                    type="file"
                    class="form-control"
                    name="image"
                    accept="image/*"
                    @input="fileHandler" />
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label for="">First Name</label>
                  <input
                    type="text"
                    class="form-control"
                    name="first_name"
                    v-model="form.first_name" />
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label for="">Last Name</label>
                  <input
                    type="text"
                    class="form-control"
                    name="last_name"
                    v-model="form.last_name" />
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label for="">Email</label>
                  <input
                    type="email"
                    class="form-control"
                    name="email"
                    v-model="form.email" />
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label for="">Phone</label>
                  <input
                    type="tel"
                    class="form-control"
                    name="phone"
                    v-model="form.phone" />
                </div>
              </div>

              <div class="col-md-12 mb-3">
                <div class="form-group">
                  <label for="">Bank Account Number</label>
                  <input
                    type="number"
                    class="form-control"
                    name="bank_account_number"
                    v-model="form.bank_account_number" />
                </div>
              </div>
              <div class="col-md-12 mb-3">
                <div class="form-group">
                  <label for="">About</label>
                  <textarea
                    class="form-control"
                    name="about"
                    v-model="form.about" />
                </div>
              </div>
              <div class="col-md-12 mb-3">
                <button type="submit" class="btn btn-dark">
                  <i class="fas fa-save"></i> Update
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import Layout from "@/Layouts/App.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import type { Customer } from "@/types/Customer";
import { useAvatar } from "@/Composables/useAvatar";

defineOptions({ layout: Layout });

const { customer } = defineProps<{
  customer: Customer;
}>();

const page = usePage();

const { generateAvatar } = useAvatar();

const form = useForm<{
  image: File | null;
  first_name: string;
  last_name: string;
  email: string;
  phone: string;
  bank_account_number: string;
  about: string;
}>({
  image: null,
  first_name: customer.first_name,
  last_name: customer.last_name,
  email: customer.email,
  phone: customer.phone,
  bank_account_number: customer.bank_account_number,
  about: customer.about || "",
});

const fileHandler = (e: Event) => {
  const target = e.target as HTMLInputElement;
  if (target.files) {
    form.image = target.files[0];
  }
};

const formHandler = () =>
  form.post(
    route("customers.update", { _method: "put", customer: customer.id })
  );
</script>
