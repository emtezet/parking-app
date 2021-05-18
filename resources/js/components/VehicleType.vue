<template>
    <div class="row">
        <div class="col-12">
            <p class="h3 mt-3 border-bottom">Typy pojazdów</p>
        </div>

        <div class="col-12 col-md-9" v-if="user_role === 'admin'">
            <p class="mt-3 h5">Dodawanie/edycja typu pojazdu</p>
            <form @submit.prevent="addVehicleType" class="mb-3">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Nazwa" v-model="vehicle_type.name">
                </div>

                <button type="submit" class="btn btn-success">Zapisz</button>
            </form>
        </div>


        <div class="small col-12 col-md-4 mb-2" v-for="vehicle_type in vehicle_types" v-bind:key="vehicle_type.id">
            <div class="card card-body">
                <h3>{{ vehicle_type.name }}</h3>
                <hr>
                <button @click="editVehicleType(vehicle_type)" class="btn btn-warning btn-sm mb-2" v-if="user_role === 'admin'">Edytuj</button>
                <button @click="deleteVehicleType(vehicle_type.id)" class="btn btn-danger btn-sm" v-if="user_role === 'admin'">Usuń</button>
            </div>
        </div>


    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "VehicleType",
    props: [
        'user_role'
    ],
    data() {
        return {
            vehicle_types: [],
            vehicle_type: {
                id: '',
                name: ''
            },
            vehicle_type_id: '',
            edit: false
        };
    },
    created() {
        this.fetchVehicleTypes();
    },
    methods: {
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
        deleteVehicleType(id) {

            showConfirmModal('Czy na pewno chcesz usunąć ten typ pojazdu?', function(param) {
                fetch(`api/vehicle_type/${id}`, {
                    method: 'delete'
                })
                    .then(res => res.json())
                    .then(data => {
                        showSuccessModal('Typ pojazdu usunięty!');
                        param.clearForm();
                        param.fetchVehicleTypes();
                    })
                    .catch(err => console.log(err));
            }, [this]);

        },
        editVehicleType(vehicle_type) {
            this.edit = true;
            this.vehicle_type.id = vehicle_type.id;
            this.vehicle_type.vehicle_type_id = vehicle_type.id;
            this.vehicle_type.name = vehicle_type.name;
            jQuery("html, body").animate({ scrollTop: 0 }, "slow");
        },
        addVehicleType() {
            if (this.edit === false) {
                // Add
                fetch('api/vehicle_type', {
                    method: 'post',
                    body: JSON.stringify(this.vehicle_type),
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
                            showSuccessModal('Typ pojazdu dodany!');
                            this.fetchVehicleTypes();
                        }
                    })
                    .catch(err => console.log(err));
            } else {
                // Update
                fetch('api/vehicle_type', {
                    method: 'put',
                    body: JSON.stringify(this.vehicle_type),
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
                            showSuccessModal('Typ pojazdu poprawnie wyedytowany!');
                            this.fetchVehicleTypes();
                        }
                    })
                    .catch(err => console.log(err));
            }
        },
        clearForm() {
            this.edit = false;
            this.vehicle_type.id = null;
            this.vehicle_type.vehicle_type_id = null;
            this.vehicle_type.name = '';
        }
    }
}
</script>

<style scoped>

</style>
