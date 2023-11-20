<template>
  <div class="p-5 mb-4 box rounded-3">
    <div class="container-fluid">
      <h1 class="display-5 fw-bold mb-3">Fruit Details</h1>
      <div class="alert" :class="{ 'alert-danger': !isSuccessful, 'alert-success': isSuccessful }" role="alert"
        v-if="message">
        <span v-if="typeof message === 'string'">
          {{ message }}
        </span>
        <span v-if="typeof message === 'object'">
          <li v-for="error in message">
            {{ error }}
          </li>
        </span>
      </div>
      <form method="POST" action="#">
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="name" name="name" placeholder="Name" v-model="fruit.name">
          <label for="name">Name</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="genus" name="genus" placeholder="Genus" v-model="fruit.genus">
          <label for="name">Genus</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="family" name="family" placeholder="Family" v-model="fruit.family">
          <label for="family">Family</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="order" name="order" placeholder="Order" v-model="fruit.fruitOrder">
          <label for="order">Order</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="carbohydrates" name="carbohydrates" placeholder="Carbohydrates"
            v-model="fruit.carbohydrates">
          <label for="carbohydrates">Carbohydrates</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="protein" name="protein" placeholder="Protein"
            v-model="fruit.protein">
          <label for="protein">Protein</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="fat" name="fat" placeholder="Fat" v-model="fruit.fat">
          <label for="fat">Fat</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="sugar" name="sugar" placeholder="Sugar" v-model="fruit.sugar">
          <label for="sugar">Sugar</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="calories" name="calories" placeholder="Calories"
            v-model="fruit.calories">
          <label for="calories">Calories</label>
        </div>
        <div class="float-start">
          <a href="#"
            class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
            @click="$router.go(-1)">&laquo; Back</a>
        </div>
        <div class="float-end">
          <a href="#" type="button" class="btn btn-success btn-lg float-end" @click="createData"
            v-if="!this.$route.params.id">
            Create
          </a>
          <a href="#" type="button" class="btn btn-success btn-lg float-end" @click="updateData"
            v-if="this.$route.params.id">
            Update
          </a>
        </div>
        <div class="clearfix"></div>
      </form>
      <div class="clearfix"></div>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent } from "vue";
import FruitsApi from '@/api/fruits'
import type Fruit from '@/types/Fruit'

type ErrorMessagesType = {
  errors: {
    [key: string]: string[];
  };
};

export default defineComponent({
  name: 'fruit-form',
  data() {
    return {
      fruit: {
        id: null,
        name: '',
        genus: '',
        family: '',
        fruitOrder: '',
        carbohydrates: 0,
        protein: 0,
        fat: 0,
        sugar: 0,
        calories: 0
      } as Fruit,
      message: '' as string | object,
      isSuccessful: true,
      submitted: false,
    }
  },
  methods: {
    clearForm() {
      this.fruit = {
        id: null,
        name: '',
        genus: '',
        family: '',
        fruitOrder: '',
        carbohydrates: 0,
        protein: 0,
        fat: 0,
        sugar: 0,
        calories: 0
      }
    },
    formatErrorMessage(errorMessages: ErrorMessagesType) {
      let errors: {} = {};

      for (const field in errorMessages.errors) {
        const messages = errorMessages.errors[field];
        const fieldLabel = field.charAt(0).toUpperCase() + field.slice(1);

        messages.forEach((message: string) => {
          errors[fieldLabel] = message;
        });
      }

      this.message = errors;
    },
    async retrieveData(id: number) {
      await FruitsApi.get(id)
        .then((response: any) => {
          this.fruit = response.data;
        })
    },
    async createData() {
      await FruitsApi.new(this.fruit)
        .then((response: any) => {
          this.message = 'Fruit data has been created'
          this.isSuccessful = true
          this.submitted = true
          this.clearForm()
        })
        .catch((e: Error) => {
          this.formatErrorMessage(e.response.data)
          this.isSuccessful = false
        })
    },
    async updateData() {
      await FruitsApi.update(this.fruit.id, this.fruit)
        .then((response: any) => {
          this.message = 'Fruit data has been updated'
          this.isSuccessful = true
          this.submitted = true
        })
        .catch((e: Error) => {
          this.formatErrorMessage(e.response.data)
          this.isSuccessful = false
        })
    }
  },
  mounted() {
    // @INFO: Only retrieve, if the id is provided in the /fruit route
    if (this.$route.params.id) {
      this.retrieveData(this.$route.params.id)
    }

    this.message = ''
  },
  onUnmounted() {
    this.clearForm()
    this.message = ''
  }
})



</script>

<style lang="scss" scoped></style>
