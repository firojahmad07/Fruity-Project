<script>
import PageHeader from "@/js/views/common/header";
import axios from "axios";

/**
 * favorite fruit component
 */
export default {
    components: { PageHeader },
    data() {
        return {
            favoriteFruites: [],
            sumOfNutritions: {},
            apiUrl: '/fetch/favorites/fruits',
            removeFromFavoriteUrl: '/remove/fruit/favorite',
            currentPage: 1,
            limit: 10,
            families: ['test'],
            paginations: [1, 2, 3, 4, 5],
        };
    },
    computed: {
    },
    mounted() {
        this.loadFavoriteFruits();
    },
    methods: {
        removeFromFavorite(fruitId) {
            axios.post(this.removeFromFavoriteUrl, { fruitId: fruitId })
                .then((response) => {
                    this.loadFavoriteFruits();
            });
        },
        loadFavoriteFruits() {
            let url = location.origin + this.apiUrl;
            axios.get(url)
                .then((response) => {
                    this.favoriteFruites = response.data.favoriteFruites;
                    this.sumOfNutritions = response.data.sumofnutritions;
                });
        }
    }
};
</script>

<template>
    <div>
        <PageHeader />
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <h1 class="table-title">Fruits List</h1>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Genus</th>
                                <th scope="col">Name</th>
                                <th scope="col">Family</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table" v-for="fruit in favoriteFruites">
                                <td>{{ fruit.id }}</td>
                                <td>{{ fruit.genus }}</td>
                                <td>{{ fruit.name }}</td>
                                <td>{{ fruit.family }} </td>
                                <td>
                                    <button type="button" class="btn btn-outline-primary" v-on:click='removeFromFavorite(fruit.id)'>Remove</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-3">
                    <h1 class="table-title">Sum Of Nutritions</h1>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Nutriants</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table" v-for="(value, index) in Object.entries(sumOfNutritions)">
                                <td>{{ value[0] }}</td>
                                <td>{{ value[1] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .table-title {
        margin-top: 20px;
    }
</style>