<template>
    <div>
        <h1 class="mb-6">Clients → Add New Client</h1>

        <!-- Form validation errors -->
        <div v-if="errors.length" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input:</span>
            <ul class="mt-2 list-disc list-inside text-sm">
                <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
            </ul>
            <button @click="errors = []" class="absolute top-0 bottom-0 right-0 px-4 py-3 text-xl leading-none">
                &times;
            </button>
        </div>

        <div class="max-w-lg mx-auto">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control" v-model="client.name">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" class="form-control" v-model="client.email">
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" class="form-control" v-model="client.phone">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" class="form-control" v-model="client.address">
            </div>
            <div class="flex">
                <div class="form-group flex-1">
                    <label for="city">City</label>
                    <input type="text" id="city" class="form-control" v-model="client.city">
                </div>
                <div class="form-group flex-1">
                    <label for="postcode">Postcode</label>
                    <input type="text" id="postcode" class="form-control" v-model="client.postcode">
                </div>
            </div>

            <div class="text-right">
                <a href="/clients" class="btn btn-default">Cancel</a>
                <button @click="storeClient" class="btn btn-primary">Create</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ClientForm',

    data() {
        return {
            client: {
                name: '',
                email: '',
                phone: '',
                address: '',
                city: '',
                postcode: '',
            },
            errors: []
        }
    },

    methods: {
        storeClient() {
            this.errors = [];

            axios.post('/clients', this.client)
                .then(response => {
                    window.location.href = response.data.url;
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        const responseErrors = error.response.data.errors;
                        this.errors = Object.values(responseErrors).flat();
                    } else {
                        // Other potential ones 
                        this.errors = ['An unexpected error occurred. Please try again.'];
                    }
                });
        }
        
    }
}
</script>
