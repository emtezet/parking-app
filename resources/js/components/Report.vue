<template>
    <div class="row">
        <div class="col-12">
            <p class="h3 mt-3 border-bottom">Generowanie raportów</p>
        </div>

        <div class="col-12 col-md-9" v-if="user_role === 'admin'">
            <p class="mt-3 h5">Generuj raport</p>
            <form @submit.prevent="generateReport" class="mb-3">

                <div class="form-group">
                    Data od <input @change="hideReport" type="date" class="form-control" placeholder="Data od" v-model="reportDayFrom">
                </div>

                <div class="form-group">
                    Data do
                    <input type="date" @change="hideReport" class="form-control" placeholder="Data do" v-model="reportDayTo">
                </div>

                <div class="form-group">
                    Pojazd
                    <select @change="hideReport" class="form-control" v-model="vehicle">
                        <option disabled value="">Pojazd</option>
                        <option v-for="vehicle in vehicles" v-bind:value="{id: vehicle.id, registration_number: vehicle.registration_number}" >
                            {{ vehicle.registration_number }} - {{ vehicle.vehicle_type_name }}
                        </option>
                    </select>
                </div>

                <button type="button" @click="generateReport"  class="btn btn-success">Generuj</button>
                <button type="button" @click="generateReportCSV"  class="btn btn-success">Generuj do pliku CSV</button>
            </form>
        </div>

        <div class="col-12 col-md-12" v-if="reportVisibility">
            <div class="card">
                <div class="h5 card-header">
                    Raport za okres <span class="font-weight-bold">{{ reportDayFrom }}</span> do <span class="font-weight-bold">{{ reportDayTo }}</span> dla pojazdu nr rej. <span class="font-weight-bold">{{ vehicle.registration_number }}</span>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Parkowania:</h5>
                    <ul class="list-group list-group-flush" >
                        <li class="list-group-item" v-for="(rent, index) in rents" v-bind:key="rent.id">
                            {{ index + 1 }}) Parkowanie od {{ rent.start_time }} do {{ rent.end_time }}<br/>
                            Parking: {{ rent.parking_name }}<br/>
                            Koszt: {{ rent.price }} zł
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-muted text-right">
                    Suma do zapłaty: <span class="font-weight-bold">{{ reportSum }} zł</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Report",
    props: [
        'user_role'
    ],
    data() {
        return {
            reportVisibility: false,
            reportDayFrom: '',
            reportDayTo: '',
            reportSum: '',
            reportVehicleRegistrationNumber: '',

            rents: [],
            vehicles: [],
            rent: {
                'id': '',
                'parking_id': '',
                'parking_name': '',
                'vehicle_id': '',
                'vehicle_registration_number': '',
                'start_time': '',
                'price': '',
                'end_time': ''
            },
            vehicle_id: '',
            vehicle: {
                id: '',
                registration_number: ''
            },
        };
    },
    created() {
        this.fetchVehicles();
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
        generateReport() {
            fetch('api/rent/get_report', {
                method: 'post',
                body: JSON.stringify({
                    date_from: this.reportDayFrom,
                    date_to: this.reportDayTo,
                    vehicle_id: this.vehicle.id
                }),
                headers: {
                    'content-type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(res => {
                    if(res.errors) {
                        showErrorModal(res.errors)
                    } else {
                        this.showReport();
                        this.rents = res.data;
                        this.reportSum = res.price
                    }
                })
                .catch(err => console.log(err));
        },
        generateReportCSV() {
            fetch('api/rent/get_report_csv', {
                method: 'post',
                body: JSON.stringify({
                    date_from: this.reportDayFrom,
                    date_to: this.reportDayTo,
                    vehicle_id: this.vehicle.id
                }),
                headers: {
                    'content-type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => {

                    if(res.status == '422') {
                        res.json().then(err => {
                            showErrorModal(err.errors)
                        })
                    } else {
                        res.blob().then(res2 => {
                            const url = window.URL.createObjectURL(res2);
                            const link = document.createElement("a");
                            link.href = url;
                            link.setAttribute("download", this.reportDayFrom + '_' +  this.reportDayTo + '_' + this.vehicle.registration_number + "_rents.csv");
                            document.body.appendChild(link);
                            link.click();
                        });
                    }
                })
                .catch(err => {
                    console.log(err)
                });
        },
        showReport() {
            this.reportVisibility = true;
        },
        hideReport() {
            this.reportVisibility = false;
        }
    }
}
</script>

<style scoped>

</style>
