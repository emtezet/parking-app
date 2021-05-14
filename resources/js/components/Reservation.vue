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
            if (confirm('Czy na pewno chcesz usunąć rezerwację?')) {
                fetch(`api/reservation/${id}`, {
                    method: 'delete'
                })
                    .then(res => res.json())
                    .then(data => {
                        alert('Rezerwacja usunięta!');
                        this.fetchReservations();
                    })
                    .catch(err => console.log(err));
            }
        },
        addRentFromReservation(id) {
            if (confirm('Czy na pewno chcesz rozpocząć parkowanie?')) {
                fetch(`api/rent/from_reservation/${id}`, {
                    method: 'post'
                })
                    .then(res => res.json())
                    .then(data => {
                        showSuccessModal('Parkowanie dodane!');
                        this.fetchReservations();
                    })
                    .catch(err => console.log(err));
            }
        },
    }
}
</script>

<style scoped>

</style>
