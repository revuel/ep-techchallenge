<template>
    <div>
        <h1>
            Clients
            <a href="/clients/create" class="float-right btn btn-primary">+ New Client</a>
        </h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Number of Bookings</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="client in localClients" :key="client.id">
                    <td>{{ client.name }}</td>
                    <td>{{ client.email }}</td>
                    <td>{{ client.phone }}</td>
                    <td>{{ client.bookings_count }}</td>
                    <td>
                        <a class="btn btn-primary btn-sm" :href="`/clients/${client.id}`">View</a>
                        <button class="btn btn-danger btn-sm" @click="deleteClient(client)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ClientsList',

    props: ['clients'],

    data() {
        return {
            localClients: [...this.clients], // make a deep local copy to re-render
        };
    },

    methods: {
        deleteClient(client) {
            // I find it dangerous to just remove the client, so I added this dummy confirmation
            if (!confirm(`Are you sure you want to delete ${client.name}?`)) return;

            axios.delete(`/clients/${client.id}`)
                .then(() => {
                    // Remove client from local list
                    this.localClients = this.localClients.filter(c => c.id !== client.id);
                })
                .catch(error => {
                    console.error('Failed to delete client:', error);
                    alert('An error occurred while deleting the client.');
                });
        }
    }
}
</script>