<template>
  <div class="row g-5">
    <div class="col-md-5 col-lg-4 order-md-last">
      <h4 class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-primary">Nutrition</span>
      </h4>
      <ul class="list-group mb-3">
        <li class="list-group-item d-flex justify-content-between lh-sm">
          <div>
            <h6 class="my-0">Carbohydate</h6>
            <small class="text-muted">Organic compounds, including sugars and starches, providing energy</small>
          </div>
          <span class="text-muted text-strong">{{ fruit.carbohydrates }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between lh-sm">
          <div>
            <h6 class="my-0">Protein</h6>
            <small class="text-muted">An essential macromolecules composed of amino acids, serving diverse biological
              functions, such as structure, enzymes, and signaling in living organisms.</small>
          </div>
          <span class="text-muted">{{ fruit.protein }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between lh-sm">
          <div>
            <h6 class="my-0">Fat</h6>
            <small class="text-muted">Lipids storing energy, aiding in insulation and cushioning, composed of fatty
              acids</small>
          </div>
          <span class="text-muted">{{ fruit.fat }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between lh-sm">
          <div>
            <h6 class="my-0">Sugar</h6>
            <small class="text-muted">Simple carbohydrates, providing quick energy, and include glucose and
              fructose.</small>
          </div>
          <span class="text-muted">{{ fruit.sugar }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between">
          <div>
            <h6 class="my-0">Calories</h6>
            <small>Units of energy derived from food, utilized by the body for various functions, including metabolism,
              movement, and maintaining physiological processes.</small>
          </div>
          <strong>{{ fruit.calories }} kcal</strong>
        </li>
      </ul>
    </div>
    <div class="col-md-7 col-lg-8">
      <h4 class="mb-3">Fruit Details <span class="text-muted fs-6 fst-italic">{{ translateSource(fruit.source) }}</span>
      </h4>
      <div class="row g-3">
        <div class="col-sm-6">
          <div for="firstName" class="form-label">Name</div>
          <h6 class="font-weight-bold">{{ fruit.name }}</h6>
        </div>
        <div class="col-sm-6">
          <div class="form-label">Family</div>
          <h6 class="font-weight-bold">{{ fruit.family }}</h6>
        </div>

        <hr class="my-4">
        <div class="col-sm-6">
          <div for="firstName" class="form-label">Genus</div>
          <h6 class="font-weight-bold">{{ fruit.genus }}</h6>
        </div>
        <div class="col-sm-6">
          <div class="form-label">Order</div>
          <h6 class="font-weight-bold">{{ fruit.fruitOrder }}</h6>
        </div>
        <hr class="my-4">
        <div class="col-sm-6">
          <div class="form-label">Created At</div>
          <h6 class="font-weight-bold">{{ formatDate(fruit.createdAt?.date) }}</h6>
        </div>
        <div class="col-sm-6">
          <div class="form-label">Updated At</div>
          <h6 class="font-weight-bold">{{ formatDate(fruit.updatedAt?.date) }}</h6>
        </div>
      </div>
      <hr class="my-4">

      <div class="float-end">
      <router-link
        class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
        :to="'/fruit/' + fruit.id">Update</router-link>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent } from "vue";
import FruitsApi from '@/api/fruits'
import type Fruit from '@/types/Fruit'

export default defineComponent({
  name: 'view-fruit',
  data() {
    return {
      fruit: {
        createdAt: {
          date: '1970-01-01T00:00:00.000Z'
        },
        updatedAt: {
          date: '1970-01-01T00:00:00.000Z'
        }
      } as Fruit,
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
  },
  mounted() {
    this.retrieveData(parseInt(this.$route.params.id))
  }
})
</script>
