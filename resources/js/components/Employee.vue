<template>
    <div class="row">
        <div class="col-12">
            <p class="h3 mt-3 border-bottom">Pracownicy</p>
        </div>

        <div class="col-12 col-md-9" v-if="user_role === 'admin'">
            <p class="mt-3 h5">Dodawanie/edycja pracownika</p>
            <form @submit.prevent="addEmployee" class="mb-3">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Imię i nazwisko" v-model="employee.name">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Adres email" v-model="employee.email">
                </div>

                <div class="form-group">
                    <select class="form-control" v-model="employee.role">
                        <option disabled value="">Rola</option>
                        <option value="admin">Administrator</option>
                        <option value="insert">Ochroniarz</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Zapisz</button>
            </form>
        </div>


        <div class="small col-12 col-md-4 mb-2" v-for="employee in employees" v-bind:key="employee.id">
            <div class="card card-body">
                <h3>{{ employee.name }}</h3>
                <h5>{{ employee.role === 'admin' ? 'Administrator' : 'Ochroniarz' }}</h5>
                <p class="mb-0">{{ employee.email }}</p>
                <hr>

                <button @click="editEmployee(employee)" class="btn btn-warning btn-sm mb-2" v-if="user_role === 'admin'">Edytuj
                </button>
                <button @click="deleteEmployee(employee.id)" class="btn btn-danger btn-sm" v-if="user_role === 'admin'">Usuń
                </button>
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
            employees: [],
            employee: {
                id: '',
                name: '',
                email: '',
                role: ''
            },

            employee_id: '',
            edit: false
        };
    },
    created() {
        this.fetchEmployees();
    },
    methods: {
        fetchEmployees(page_url) {
            let vm = this;
            page_url = page_url || '/api/employees';
            fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.employees = res.data;
                })
                .catch(err => console.log(err));
        },
        deleteEmployee(id) {
            showConfirmModal('Czy na pewno chcesz usunąć tego użytkownika?', function (param){
                fetch(`api/employee/${id}`, {
                    method: 'delete'
                })
                    .then(res => res.json())
                    .then(data => {
                        showSuccessModal('Pracownik usunięty!');
                        param.clearForm();
                        param.fetchEmployees();
                    })
                    .catch(err => console.log(err));
            },[this])
        },
        editEmployee(employee) {
            this.edit = true;
            this.employee.id = employee.id;
            this.employee.employee_id = employee.id;
            this.employee.name = employee.name;
            this.employee.email = employee.email;
            this.employee.role = employee.role;
        },
        addEmployee() {
            if (this.edit === false) {
                // Add
                fetch('api/employee', {
                    method: 'post',
                    body: JSON.stringify(this.employee),
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
                            this.clearForm();
                            showSuccessModal('Pracownik dodany!');
                            this.fetchEmployees();
                        }

                    })
                    .catch(err => console.log(err));
            } else {
                // Update
                fetch('api/employee', {
                    method: 'put',
                    body: JSON.stringify(this.employee),
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
                            this.clearForm();
                            showSuccessModal('Pracownik poprawnie wyedytowany!');
                            this.fetchEmployees();
                        }
                    })
                    .catch(err => console.log(err));
            }
        },
        clearForm() {
            this.edit = false;
            this.employee.id = null;
            this.employee.employee_id = null;
            this.employee.name = '';
            this.employee.email = '';
            this.employee.role = '';
        }
    }
}
</script>

<style scoped>

</style>
