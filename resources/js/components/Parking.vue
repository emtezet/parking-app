<template>
    <div class="row">
        <div class="col-12">
            <p class="h3 mt-3 border-bottom">Parkingi</p>
        </div>

        <div class="col-12 col-md-7" v-if="user_role === 'admin'">
            <p class="mt-3 h5">Dodawanie/edycja parkingu</p>
            <form @submit.prevent="addParking" class="mb-3">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Nazwa" v-model="parking.name">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Adres" v-model="parking.address">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Ilość miejsc" v-model="parking.places_amount">
                </div>

                <button type="submit" class="btn btn-success">Zapisz</button>
            </form>
        </div>


        <div class="small col-12 col-md-6 mb-2" v-for="parking in parkings" v-bind:key="parking.id">
            <div class="card card-body">
                <h3>{{ parking.name }}</h3>
                <p class="mb-0">{{ parking.address }}</p>
                <hr>
                {{ parking.free_places}}/{{ parking.places_amount }} wolnych miejsc


                <button @click="editParking(parking)" class="btn btn-warning mb-2" v-if="user_role === 'admin'">Edytuj</button>
                <button @click="deleteParking(parking.id)" class="btn btn-danger" v-if="user_role === 'admin'">Usuń</button>
                <button @click="makeReservation(parking.id)" class="btn btn-info text-white" v-if="user_role === '' && parking.free_places > 0">Zatrezerwuj</button>
            </div>
        </div>


    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "Parking",
    props: [
      'user_role'
    ],
    data() {
        return {
            parkings: [],
            parking: {
                id: '',
                name: '',
                address: '',
                places_amount: '',
                free_places: ''
            },
            parking_id: '',
            edit: false
        };
    },
    created() {
        this.fetchParkings();
    },
    methods: {
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
        deleteParking(id) {
            if (confirm('Czy na pewno chcesz usunąć ten parking?')) {
                fetch(`api/parking/${id}`, {
                    method: 'delete'
                })
                    .then(res => res.json())
                    .then(data => {
                        alert('Parking usunięty!');
                        this.clearForm();
                        this.fetchParkings();
                    })
                    .catch(err => console.log(err));
            }
        },
        editParking(parking) {
            this.edit = true;
            this.parking.id = parking.id;
            this.parking.parking_id = parking.id;
            this.parking.name = parking.name;
            this.parking.address = parking.address;
            this.parking.places_amount = parking.places_amount;
        },
        addParking() {
            if (this.edit === false) {
                // Add
                fetch('api/parking', {
                    method: 'post',
                    body: JSON.stringify(this.parking),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.clearForm();
                        alert('Parking dodany!');
                        this.fetchParkings();
                    })
                    .catch(err => console.log(err));
            } else {
                // Update
                fetch('api/parking', {
                    method: 'put',
                    body: JSON.stringify(this.parking),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.clearForm();
                        alert('Parking poprawnie wyedytowany!');
                        this.fetchParkings();
                    })
                    .catch(err => console.log(err));
            }
        },
        clearForm() {
            this.edit = false;
            this.parking.id = null;
            this.parking.parking_id = null;
            this.parking.name = '';
            this.parking.address = '';
            this.parking.places_amount = '';
        }
    }
}
</script>

<style scoped>

</style>
