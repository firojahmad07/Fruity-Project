<script>
import PageHeader from "@/js/views/common/header";
import axios from "axios";

/**
 * Fruits Component
 */
export default {
    components: { PageHeader },
    data() {
        return {
            fruits: [],
            totalResults: null,
            familyFilter: [],
            apiUrl: '/fetch/fruits',
            addToFavoriteUrl: '/add/fruit/favorite',
            currentPage: 1,
            limit: 10,
            families: [],
            searchName: '',
            pagination: [],
        };
    },
    computed: {
    },
    mounted() {
        this.loadFruits();
    },
    methods: {
        addToFavorite(fruitId) {
            axios.post(this.addToFavoriteUrl, { fruitId: fruitId })
                .then((response) => {
                    this.loadFruits();
            });
        },
        loadFruits() {
            let url = location.origin + this.apiUrl;
            axios.get(url, { params: { page: this.currentPage, limit: this.limit, families: this.families, search: this.searchName } })
                .then((response) => {
                    this.fruits = response.data.fruits;
                    this.familyFilter = response.data.familyFilter;
                    this.currentPage = response.data.currentPage;
                    this.totalResults = response.data.totalResults;
                    this.pagination = response.data.pagination;
                });
        },
        runfamilyFilter() {
            this.loadFruits()
        },
        searchByName() {
            this.loadFruits();
        },
        paginateData(pageNumber) {
            this.currentPage = pageNumber;
            this.loadFruits();
        },
        nextPage() {
            this.currentPage++;
            this.loadFruits();
        },
        previousPage() {
            this.currentPage = this.currentPage - 1;
            this.loadFruits();
        }
    }
};
</script>

<template>
    <div>
        <PageHeader />
        <div class="container-fluid">
            <h2 class="table-title">Total Fruits : {{ totalResults }} </h2>
            <div class="row">
                <div class="col-md-9">
                    <form class="d-flex">
                        <input class="form-control me-sm-2" type="search" v-model="searchName" placeholder="Search">
                        <button class="btn btn-secondary my-2 my-sm-0" type="submit" v-on:click="searchByName()">Search</button>
                    </form>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="exampleSelect1" multiple v-model="families" v-on:change="runfamilyFilter()">
                      <option v-for="filterData in familyFilter" :value="filterData.name" > {{ filterData.name }} </option>
                    </select>
                </div>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Genus</th>
                        <th scope="col">Name</th>
                        <th scope="col">Family</th>
                        <th scope="col">Is favorite</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table" v-for="fruit in fruits">
                        <td>{{ fruit.id }}</td>
                        <td>{{ fruit.genus }}</td>
                        <td>{{ fruit.name }}</td>
                        <td>{{ fruit.family }} </td>
                        <td><span v-if="fruit.is_favorite" class="badge bg-success">favorite</span></td>
                        <td>
                            <button type="button" class="btn btn-outline-primary" v-on:click='addToFavorite(fruit.id)'>Add</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div>
                <ul class="pagination">
                    <li class="page-item " v-bind:class="{ 'disabled': currentPage <= 1 }">
                        <a class="page-link" href="#" v-on:click="previousPage()">&laquo;</a>
                    </li>
                    <span >

                    </span>
                    <li class="page-item" v-for="paginationData in pagination" v-bind:class="{ 'active': paginationData == currentPage }">
                        <a class="page-link" href="#" v-on:click="paginateData(paginationData)">{{ paginationData }}</a>
                    </li>
                    <li class="page-item" v-bind:class="{ 'disabled': currentPage == pagination.length }">
                        <a class="page-link" href="#" v-on:click="nextPage()">&raquo;</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<style>
    .table-title {
        margin-top: 20px;
    }
</style>