<template>
  <div class="p-5 mb-4 box rounded-3">
    <div class="container-fluid">
      <h1 class="display-5 fw-bold mb-3">Fruit Details</h1>
      <form method="POST" action="#">
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="name" name="name" placeholder="Name" v-model="fruit.name">
          <label for="name">Name</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="genus" name="genus" placeholder="Genus" v-model="fruit.genus">
        </div>
        <label for="genus">jGenus</label>
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
        <button type="submit" class="btn btn-success btn-lg float-end">
          Submit
        </button>
      </form>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent } from "vue";
import FruitsApi from '@/api/fruits'
import type Fruit from '@/types/Fruit'

export default defineComponent({
  name: 'form',
  data() {
    return {
      fruit: {} as Fruit,
      message: '' as string,
    }
  },
  methods: {
    async retrieveData(id: number) {
      await FruitsApi.get(id)
        .then((response: any) => {
          console.log(response);
          this.fruit = response.data;
        })
    },

    async updateData() {
      await FruitsApi.update(this.fruit.id, this.fruit)
      .then((response: any) => {
        this.message = 'Fruit data has been updated'
      })
      .error((e: Error) => {
        console.error(e);
      })
    }
  },
  mounted() {
    this.retrieveData(this.$route.params.id)
    this.message = ''
  },
  onUnmounted() {
    this.fruit = {}
    this.message = ''
  }
})



</script>

<style lang="scss" scoped></style>
