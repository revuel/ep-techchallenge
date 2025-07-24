import { shallowMount } from '@vue/test-utils';
import ClientShow from '../ClientShow.vue';

// Helpers
function getDate(offsetDays) {
  const date = new Date();
  date.setDate(date.getDate() + offsetDays);
  return date.toISOString();
}

describe('ClientShow.vue', () => {
  let wrapper;

  const client = {
    name: 'Aaron A. Aaronson',
    email: 'aaa@example.com',
    phone: '000 000 000',
    address: 'Morning Star Street',
    postcode: '31415',
    city: 'Neon City',
    bookings: [
      { id: 1, start: getDate(-2), end: getDate(-2), notes: 'Past booking' },
      { id: 2, start: getDate(0), end: getDate(0), notes: 'Today booking' },
      { id: 3, start: getDate(2), end: getDate(2), notes: 'Future booking' }
    ]
  };

  beforeEach(() => {
    wrapper = shallowMount(ClientShow, {
      propsData: { client }
    });
  });

  it('shows all bookings by default', () => {
    expect(wrapper.vm.bookingFilter).toBe('all');
    expect(wrapper.vm.filteredBookings).toHaveLength(3);
  });

  it('filters future bookings correctly (including today)', async () => {
    wrapper.setData({ bookingFilter: 'future' });
    await wrapper.vm.$nextTick();

    const filtered = wrapper.vm.filteredBookings;
    expect(filtered).toHaveLength(2);
    expect(filtered.map(b => b.notes)).toEqual([
      'Today booking',
      'Future booking'
    ]);
  });

  it('filters past bookings correctly', async () => {
    wrapper.setData({ bookingFilter: 'past' });
    await wrapper.vm.$nextTick();

    const filtered = wrapper.vm.filteredBookings;
    expect(filtered).toHaveLength(1);
    expect(filtered[0].notes).toBe('Past booking');
  });
});