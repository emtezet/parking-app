<template>
    <div class="row">
        <div class="col-12">
            <p class="h3 mt-3 border-bottom">Cenniki</p>
        </div>

        <div class="col-12 col-md-9" v-if="user_role === 'admin'">
            <p class="mt-3 h5">Dodawanie/edycja cenników</p>
            <form @submit.prevent="addPriceList" class="mb-3">


                <div class="form-group">
                    <select class="form-control" v-model="price_list.vehicle_type_id">
                        <option disabled value="">Typ pojazdu</option>
                        <option v-for="vehicle_type in vehicle_types" v-bind:value="vehicle_type.id">
                            {{ vehicle_type.name }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <select class="form-control" v-model="price_list.parking_id">
                        <option disabled value="">Parking</option>
                        <option v-for="parking in parkings" v-bind:value="parking.id">
                            {{ parking.name }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Stawka na godzinę" v-model="price_list.price_per_hour">
                </div>

                <button type="submit" class="btn btn-success">Zapisz</button>
            </form>
        </div>


        <div class="small col-12 col-md-4 mb-2" v-for="price_list in price_lists" v-bind:key="price_list.id">
            <div class="card card-body">
                <h5>
                    Parking: {{ price_list.parking_name }} <br/>
                    Typ pojazdu: {{ price_list.vehicle_type_name }}
                </h5>
                <p class="h4 mb-0">Stawka: {{ price_list.price_per_hour }} zł/h</p>
                <hr>

                <button @click="editPriceList(price_list)" class="btn btn-warning btn-sm mb-2" v-if="user_role === 'admin'">Edytuj
                </button>
                <button @click="deletePriceList(price_list.id)" class="btn btn-danger btn-sm" v-if="user_role === 'admin'">Usuń
                </button>
            </div>
        </div>


    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "PriceList",
    props: [
        'user_role'
    ],
    data() {
        return {
            price_lists: [],
            vehicle_types: [],
            parkings: [],

            price_list: {
                id: '',
                price_per_hour: '',
                vehicle_type_id: '',
                parking_id: '',
                vehicle_type_name: '',
                parking_name: '',
            },
            vehicle_type: {
                id: '',
                name: ''
            },
            parking: {
                id: '',
                name: ''
            },
            price_list_id: '',
            edit: false
        };
    },
    created() {
        this.fetchPriceLists();
        this.fetchVehicleTypes();
        this.fetchParkings();
    },
    methods: {
        fetchPriceLists(page_url) {
            let vm = this;
            page_url = page_url || '/api/price_lists';
            fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.price_lists = res.data;
                })
                .catch(err => console.log(err));
        },
        fetchVehicleTypes(page_url) {
            let vm = this;
            page_url = page_url || '/api/vehicle_types';
            fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.vehicle_types = res.data;
                })
                .catch(err => console.log(err));
        },
        fetchParkings(page_url) {
            let vm = this;
            page_url = page_url || '/api/parkings';
            fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.parkings = res.data;
                })
                .catch(err => console.log(err));
        },
        deletePriceList(id) {
            if (confirm('Czy na pewno chcesz usunąć ten cennik?')) {
                fetch(`api/price_list/${id}`, {
                    method: 'delete'
                })
                    .then(res => res.json())
                    .then(data => {
                        alert('Cennik usunięty!');
                        this.clearForm();
                        this.fetchPriceLists();
                    })
                    .catch(err => console.log(err));
            }
        },
        editPriceList(price_list) {
            this.edit = true;
            this.price_list.id = price_list.id;
            this.price_list.price_list_id = price_list.id;
            this.price_list.price_per_hour = price_list.price_per_hour;
            this.price_list.vehicle_type_id = price_list.vehicle_type_id;
            this.price_list.parking_id = price_list.parking_id;
        },
        addPriceList() {
            if (this.edit === false) {
                // Add
                fetch('api/price_list', {
                    method: 'post',
                    body: JSON.stringify(this.price_list),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.clearForm();
                        alert('Cennik dodany!');
                        this.fetchPriceLists();
                    })
                    .catch(err => console.log(err));
            } else {
                // Update
                fetch('api/price_list', {
                    method: 'put',
                    body: JSON.stringify(this.price_list),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.clearForm();
                        alert('Cennik poprawnie wyedytowany!');
                        this.fetchVehicles();
                    })
                    .catch(err => console.log(err));
            }
        },
        clearForm() {
            this.edit = false;
            this.price_list.id = null;
            this.price_list.price_list_id = null;
            this.price_list.price_per_hour = '';
            this.price_list.vehicle_type_id = '';
            this.price_list.parking_id = '';
        }
    }
}
</script>

<style scoped>

</style>
