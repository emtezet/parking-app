<template>
    <div class="row">
        <div class="col-12">
            <p class="h3 mt-3 border-bottom">Aktywne parkowania</p>
        </div>

        <div class="col-12 col-md-9" v-if="user_role === 'insert'">
            <p class="mt-3 h5">Dodawanie parkowania</p>
            <form @submit.prevent="addRent" class="mb-3">
                <div class="form-group">
                    <select class="form-control" v-model="rent.parking_id">
                        <option disabled value="">Parking</option>
                        <option v-for="parking in parkings" v-bind:value="parking.id">
                            {{ parking.name }}
                        </option>
                    </select>
                    <select class="form-control" v-model="rent.vehicle_id">
                        <option disabled value="">Pojazd</option>
                        <option v-for="vehicle in vehicles" v-bind:value="vehicle.id">
                            {{ vehicle.registration_number }}
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Dodaj</button>
            </form>
        </div>

        <div class="small col-12 col-md-4 mb-2" v-for="rent in rents" v-bind:key="rent.id">
            <div class="card card-body">
                <h3>Nr rejestracyjny: {{ rent.vehicle_registration_number }}</h3>
                <p class="mb-0">Parking: {{ rent.parking_name }}</p>
                <p class="mb-0">Trwa od: {{ rent.start_time }}</p>
                <hr>
                <button @click="endRent(rent.id)" class="btn btn-danger btn-sm mb-1" v-if="user_role === 'insert'">
                    Zakończ
                </button>
            </div>
        </div>


    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "Rent",
    props: [
        'user_role'
    ],
    data() {
        return {
            rents: [],
            vehicles: [],
            parkings: [],
            rent: {
                'id': '',
                'parking_id': '',
                'parking_name': '',
                'vehicle_id': '',
                'vehicle_registration_number': '',
                'start_time': '',
            },
            vehicle: {
                id: '',
                registration_number: ''
            },
            parking: {
                id: '',
                name: ''
            },

            rent_id: ''
            //edit: false
        };
    },
    created() {
        this.fetchRents();
        this.fetchVehicles();
        this.fetchParkings();
    },
    methods: {
        fetchRents(page_url) {
            let vm = this;
            page_url = page_url || '/api/rents';
            fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.rents = res.data;
                })
                .catch(err => console.log(err));
        },
        fetchVehicles(page_url) {
            let vm = this;
            page_url = page_url || '/api/vehicles';
            fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.vehicles = res.data;
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
        endRent(id) {

            showConfirmModal('Czy na pewno chcesz zakończyć to parkowanie?', function (param) {
                fetch(`api/rent/${id}`, {
                    method: 'delete',
                    headers: {
                        'content-type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if(data.errors) {
                            showErrorModal(data.errors);
                        } else {
                            showSuccessModal('Parkowanie zakończone poprawnie!');
                            param.fetchRents();
                        }
                    })
                    .catch(err => console.log(err));
            }, [this]);
        },
        addRent() {
            // Add
            fetch('api/rent', {
                method: 'post',
                body: JSON.stringify(this.rent),
                headers: {
                    'content-type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if(data.errors) {
                        showErrorModal(data.errors)
                    } else {
                        this.clearForm();
                        showSuccessModal('Parkowanie dodane!');
                        this.fetchRents();
                    }
                })
                .catch(err => console.log(err));
        },
        clearForm() {
            this.edit = false;
            this.rent.id = null;
            this.rent.parking_id = '';
            this.rent.vehicle_id = '';
        }
    }
}
</script>

<style scoped>

</style>
