<template>
    <div class="row">
        <div class="col-12">
            <p class="h3 mt-3 border-bottom">Pojazdy</p>
        </div>

        <div class="col-12 col-md-9" v-if="user_role === 'insert' || user_role === 'admin'">
            <p class="mt-3 h5">Dodawanie/edycja pojazdu</p>
            <form @submit.prevent="addVehicle" class="mb-3">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Nr rejestracyjny"
                           v-model="vehicle.registration_number">
                </div>

                <div class="form-group">
                    <select class="form-control" v-model="vehicle.vehicle_type_id">
                        <option disabled value="">Typ pojazdu</option>
                        <option v-for="vehicle_type in vehicle_types" v-bind:value="vehicle_type.id">
                            {{ vehicle_type.name }}
                        </option>
                    </select>
                </div>


                <button type="submit" class="btn btn-success">Zapisz</button>
            </form>
        </div>


        <div class="small col-12 col-md-4 mb-2" v-for="vehicle in vehicles" v-bind:key="vehicle.id">
            <div class="card card-body">
                <h3>Nr rej. {{ vehicle.registration_number }}</h3> ({{ vehicle.vehicle_type_name }})
                <hr v-if="user_role === 'admin'">
                <button @click="editVehicle(vehicle)" class="btn btn-warning btn-sm mb-2" v-if="user_role === 'admin'">
                    Edytuj
                </button>
                <button @click="deleteVehicle(vehicle.id)" class="btn btn-danger btn-sm" v-if="user_role === 'admin'">
                    Usuń
                </button>
            </div>
        </div>


    </div>
</template>

<script>
import {mapGetters} from "vuex";

class FormErrors {
    constructor() {
        this.errors = {};
    }

    get(field) {
        if (this.errors[field]) {
            return this.errors[field][0];
        }
    }

    record(errors) {
        this.errors = errors;
    }
}

export default {
    name: "Vehicle",
    props: [
        'user_role'
    ],
    data() {
        return {
            vehicles: [],
            vehicle_types: [],
            vehicle: {
                id: '',
                registration_number: '',
                vehicle_type_id: '',
                vehicle_type_name: '',
            },
            vehicle_type: {
                id: '',
                name: ''
            },
            vehicle_id: '',
            edit: false,
            form_errors: new FormErrors()
        };
    },
    created() {
        this.fetchVehicles();
        this.fetchVehicleTypes();
    },
    methods: {
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
        deleteVehicle(id) {
            if (confirm('Czy na pewno chcesz usunąć ten pojazd?')) {
                fetch(`api/vehicle/${id}`, {
                    method: 'delete'
                })
                    .then(res => res.json())
                    .then(data => {
                        alert('Pojazd usunięty!');
                        this.clearForm();
                        this.fetchVehicles();
                    })
                    .catch(err => console.log(err));
            }
        },
        editVehicle(vehicle) {
            this.edit = true;
            this.vehicle.id = vehicle.id;
            this.vehicle.vehicle_id = vehicle.id;
            this.vehicle.registration_number = vehicle.registration_number;
            this.vehicle.vehicle_type_id = vehicle.vehicle_type_id;
        },
        addVehicle() {
            if (this.edit === false) {
                // Add
                fetch('api/vehicle', {
                    method: 'post',
                    body: JSON.stringify(this.vehicle),
                    headers: {
                        'content-type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(res => res.json())
                    .then((data) => {
                        if (data.errors) {
                            showErrorModal(data.errors);
                        } else {
                            this.clearForm();
                            showSuccessModal('Pojazd dodany!');
                            this.fetchVehicles();
                        }
                    })
                    .catch((err) => {
                        console.log(err)
                    });
            } else {
                // Update
                fetch('api/vehicle', {
                    method: 'put',
                    body: JSON.stringify(this.vehicle),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.clearForm();
                        alert('Pojazd poprawnie wyedytowany!');
                        this.fetchVehicles();
                    })
                    .catch(err => console.log(err));
            }
        },
        clearForm() {
            this.edit = false;
            this.vehicle.id = null;
            this.vehicle.vehicle_id = null;
            this.vehicle.registration_number = '';
            this.vehicle.vehicle_type_id = '';
        }
    }
}
</script>

<style scoped>

</style>
