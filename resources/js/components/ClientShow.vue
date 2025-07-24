<template>
    <div>
        <h1 class="mb-6">Clients -> {{ client.name }}</h1>

        <div class="flex">
            <div class="w-1/3 mr-5">
                <div class="w-full bg-white rounded p-4">
                    <h2>Client Info</h2>
                    <table>
                        <tbody>
                            <tr>
                                <th class="text-gray-600 pr-3">Name</th>
                                <td>{{ client.name }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Email</th>
                                <td>{{ client.email }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Phone</th>
                                <td>{{ client.phone }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Address</th>
                                <td>{{ client.address }}<br/>{{ client.postcode + ' ' + client.city }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="w-2/3">
                <div>
                    <button class="btn" :class="{'btn-primary': currentTab == 'bookings', 'btn-default': currentTab != 'bookings'}" @click="switchTab('bookings')">Bookings</button>
                    <button class="btn" :class="{'btn-primary': currentTab == 'journals', 'btn-default': currentTab != 'journals'}" @click="switchTab('journals')">Journals</button>
                </div>

                <!-- Bookings -->
                <div class="bg-white rounded p-4" v-if="currentTab == 'bookings'">
                    <h3 class="mb-3">List of client bookings</h3>
                    <div class="mb-3">
                    <label for="bookingFilter" class="mr-2">Show:</label>
                    <select id="bookingFilter" v-model="bookingFilter" class="form-control d-inline-block w-auto">
                        <option value="all">All bookings</option>
                        <option value="future">Future bookings only</option>
                        <option value="past">Past bookings only</option>
                    </select>
                </div>

                    <template v-if="filteredBookings && filteredBookings.length > 0">
                        <table>
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="booking in filteredBookings" :key="booking.id">
                                    <td>{{ formatBookingTime(booking.start, booking.end) }}</td>
                                    <td>{{ booking.notes }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" @click="deleteBooking(booking)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </template>

                    <template v-else>
                        <p class="text-center">The client has no bookings.</p>
                    </template>

                </div>

                <!-- Journals -->
                <div class="bg-white rounded p-4" v-if="currentTab == 'journals'">
                    <h3 class="mb-3">List of client journals</h3>

                    <p>(BONUS) TODO: implement this feature</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { formatBookingTime } from '../utils/timeUtils';

export default {
    name: 'ClientShow',

    props: ['client'],

    data() {
        return {
            currentTab: 'bookings',
            bookingFilter: 'all',
        }
    },

    methods: {
        switchTab(newTab) {
            this.currentTab = newTab;
        },

        deleteBooking(booking) {
            axios.delete(`/bookings/${booking.id}`);
        },
        
        formatBookingTime,
    },

    computed: {
        filteredBookings() {
            const today = new Date().setHours(0, 0, 0, 0);

            return this.client.bookings.filter(booking => {
                const bookingStart = new Date(booking.start).setHours(0, 0, 0, 0);

                if (this.bookingFilter === 'future') {
                    return bookingStart >= today;
                } else if (this.bookingFilter === 'past') {
                    return bookingStart < today;
                }   

                return true;
            });
        }
    },
}
</script>