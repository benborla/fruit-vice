<template>
  <div class="row offset-md-3 col-md-9 mb-5 align-items-end">
    <div class="col">
      <label class="form-label">Sort By</label>
      <select class="form-select" aria-label="select order" v-model="orderBy" @change="retrieveData">
        <option value="name" selected>Name</option>
        <option value="family">Family</option>
        <option value="id">ID</option>
      </select>
    </div>
    <div class="col">
      <label class="form-label">Order</label>
      <select class="form-select" aria-label="select direction" v-model="direction" @change="retrieveData">
        <option value="asc" selected>Ascending</option>
        <option value="desc">Descending</option>
      </select>
    </div>
    <div class="col">
      <label class="form-label">Rows</label>
      <select class="form-select" aria-label="select rows" v-model="size" @change="retrieveData">
        <option value="5" selected>5</option>
        <option value="10">10</option>
        <option value="20">20</option>
      </select>
    </div>
    <div class="col">
      <div class="input-group">
        <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search"
          aria-describedby="search-addon" v-model="search" />
        <button type="button" class="btn btn-outline-primary" @click="retrieveData">search</button>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="col float-end mb-2">
        <router-link to="/fruit" class="btn btn-success btn-md">New Fruit</router-link>
      </div>
      <div class="clearfix"></div>
      <div class="alert" :class="{ 'alert-danger': !isSuccessful, 'alert-success': isSuccessful }" role="alert"
        v-if="message">
        {{ message }}
      </div>
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Genus</th>
            <th scope="col">Family</th>
            <th scope="col">Order</th>
            <th scope="col">Date Added</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(fruit, index) in fruits" :key="index">
            <th scope="row">{{ fruit.id }}</th>
            <td>{{ fruit.name }}</td>
            <td>{{ fruit.genus }}</td>
            <td>{{ fruit.family }}</td>
            <td>{{ fruit.fruitOrder }}</td>
            <td>{{ getFormattedDate(fruit.createdAt.date) }}</td>
            <td>
              <a href="#" @click="addToFavorite(fruit)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill"
                  viewBox="0 0 16 16">
                  <path
                    d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                </svg>
              </a> |
              <router-link :to="'/fruit/' + fruit.id + '/view'">View</router-link> |
              <router-link :to="'/fruit/' + fruit.id">Edit</router-link> |
              <button class="btn btn-link" @click="deleteFruit(fruit.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
      <nav aria-label="pagination">
        <ul class="pagination">
          <li class="page-item" :class="this.page <= 1 && 'disabled'">
            <button type="button" class="page-link" @click="previous" tabindex="-1"
              :aria-disabled="this.page <= 1 && 'true'">Previous</button>
          </li>
          <li class="page-item" v-for="n in this.paginationCount" :class="this.page === n && 'active'">
            <button class="page-link" @click="goto(n)" :aria-disabled="this.page === n && 'disabled'">{{ n }}</button>
          </li>
          <li class="page-item" :class="this.page >= this.paginationCount && 'disabled'">
            <button class="page-link" @click="next"
              :aria-disabled="this.page >= this.paginationCount && 'true'">Next</button>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import FruitsApi from '@/api/fruits'
import type Fruit from '@/types/Fruit'


export default defineComponent({
  name: 'home',
  data() {
    return {
      fruits: [] as Fruit[],
      page: 1,
      size: 5,
      paginationCount: 1,
      orderBy: 'name',
      direction: 'asc',
      search: '',
      message: '',
      isSuccessful: true,
    }
  },
  methods: {
    next() {
      this.page += 1;
      if (this.page <= this.paginationCount) {
        this.retrieveData()
      }
    },
    previous() {
      this.page -= 1;
      if (this.page >= 1) {
        this.retrieveData()
      }
    },
    goto(page: number) {
      this.page = page;
      this.retrieveData()
    },
    async retrieveData() {
      await FruitsApi.all(
        this.size,
        this.direction,
        this.orderBy,
        this.search,
        this.page
      )
        .then((response: any) => {
          const { pageSize, numResults } = response.data;
          this.paginationCount = Math.floor(numResults / pageSize)
          this.fruits = response.data.results;
        })
    },
    async deleteFruit(id: number) {
      await FruitsApi.delete(id)
        .then((response: any) => {
          this.retrieveData()
        })
    },
    async addToFavorite(fruit: Fruit) {
      await FruitsApi.favorite(fruit.id)
        .then((response: any) => {
          const { status } = response.data
          if (status === 'new') {
            this.message = `${fruit.name} has been added to favorites`
            this.isSuccessful = true
          } else {
            this.message = `${fruit.name} has already been added as favorite`
            this.isSuccessful = false
          }
        })
        .catch((e: Error) => {
          console.log(e);
          const { favorites } = e.response.data.errors
          this.message = favorites[0]
          this.isSuccessful = false
        })
    },
    getFormattedDate(date: string) {
      return new Date(date).toISOString().slice(0, 19).replace("T", " ");
    },
  },
  mounted() {
    this.retrieveData();
  },
  onUnmounted() {
    this.fruits = []
  }
})
</script>

<style lang="scss" scoped></style>
