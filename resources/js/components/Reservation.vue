<template>
    <div class="row">
        <div class="col-12">
            <p class="h3 mt-3 border-bottom">Aktywne rezerwacje</p>
        </div>

        <div class="small col-12 col-md-4 mb-2" v-for="reservation in reservations" v-bind:key="reservation.id">
            <div class="card card-body">
                <h3>Nr rejestracyjny: {{ reservation.registration_number }}</h3>
                <p class="mb-0">Parking: {{ reservation.parking_name }}</p>
                <p class="mb-0">Ważna do: {{ reservation.valid_to }}</p>
                <hr>
                <button @click="deleteReservation(reservation.id)" class="btn btn-danger btn-sm mb-1" v-if="user_role === 'insert'">Usuń</button>
                <button @click="addRentFromReservation(reservation.id)" class="btn btn-success btn-sm" v-if="user_role === 'insert'">Dodaj parkowanie</button>
            </div>
        </div>


    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "Reservation",
    props: [
        'user_role'
    ],
    data() {
        return {
            reservations: [],
            reservation: {
                id: '',
                registration_number: '',
                parking_name: '',
                valid_to: ''
            },
            reservation_id: ''
            //edit: false
        };
    },
    created() {
        this.fetchReservations();
    },
    methods: {
        fetchReservations(page_url) {
            let vm = this;
            page_url = page_url || '/api/reservations';
            fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.reservations = res.data;
                })
                .catch(err => console.log(err));
        },
        deleteReservation(id) {

            showConfirmModal('Czy na pewno chcesz usunąć rezerwację?', function(param) {
                fetch(`api/reservation/${id}`, {
                    method: 'delete'
                })
                    .then(res => res.json())
                    .then(data => {
                        if(data.errors) {
                            showErrorModal(data.errors)
                        } else {
                            showSuccessModal('Rezerwacja usunięta!');
                            param.fetchReservations();
                        }
                    })
                    .catch(err => console.log(err));
            }, [this]);
        },
        addRentFromReservation(id) {

            showConfirmModal('Czy na pewno chcesz rozpocząć parkowanie?', function (param) {
                fetch(`api/rent/from_reservation/${id}`, {
                    method: 'post',
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
                            showSuccessModal('Parkowanie dodane!');
                            param.fetchReservations();
                        }
                    })
                    .catch(err => console.log(err));
            }, [this]);
        },
    }
}
</script>

<style scoped>

</style>
