<template>
  <div class="row">
    <div class="col-md-12">
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Fruit Name</th>
            <th scope="col">Date Added</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(favorite, index) in favorites" :key="index">
            <td>{{ index + 1 }}</td>
            <td><router-link class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" :to="'/fruit/' + favorite.fruit_id + '/view'">{{ favorite.fruit_name }}</router-link></td>
            <td>{{ formatDate(favorite.dateAdded.date) }}</td>
            <td>
              <button type="button" class="btn btn-link text-danger link-offset-2 link-offset-3-hover link-underline-danger link-underline-opacity-0 link-underline-opacity-75-hover" @click="removeFavorite(favorite.fruit_id)">Remove</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent } from "vue";
import FruitsApi from '@/api/fruits'
import type Favorite from '@/types/Favorite'

export default defineComponent({
  name: 'favorites',
  data() {
    return {
      favorites: [] as Favorite[]
    }
  },
  methods: {
    getFormattedDate(date: string) {
      return new Date(date).toISOString().slice(0, 19).replace("T", " ");
    },
    async getFavorites() {
      await FruitsApi.favorites()
        .then((response: any) => {
          this.favorites = response.data
        })
    },
    async removeFavorite(id: number) {
      await FruitsApi.removeFavorite(id)
        .then((response: any) => {
          this.getFavorites()
        })
    },
  },
  mounted() {
    this.getFavorites()
  },
})
</script>
