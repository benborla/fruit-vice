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
            <td><router-link :to="'/fruit/' + favorite.fruit_id + '/view'">{{ favorite.fruit_name }}</router-link></td>
            <td>{{ getFormattedDate(favorite.dateAdded.date) }}</td>
            <td>
              <a href="#" @click="removeFavorite(favorite.fruit_id)">Remove</a>
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

export default defineComponent({
  name: 'favorites',
  data() {
    return {
      favorites: []
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
          console.log(response.data)
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
    console.log('went here')
    this.getFavorites()
  },
})
</script>
