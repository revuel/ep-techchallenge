import { mount } from '@vue/test-utils';
import ClientsList from '../ClientsList.vue';
import axios from 'axios';

jest.mock('axios');

const mockClients = [
    { id: 1, name: 'Alice', email: 'alice@example.com', phone: '123', bookings_count: 2 },
    { id: 2, name: 'Bob', email: 'bob@example.com', phone: '456', bookings_count: 3 }
];

describe('ClientsList.vue', () => {
    let confirmSpy;

    beforeEach(() => {
        // reset purposes
        axios.delete.mockClear();
        confirmSpy = jest.spyOn(window, 'confirm');
    });

    afterEach(() => {
        jest.restoreAllMocks();
    });

    it('renders client list correctly', () => {
        const wrapper = mount(ClientsList, {
            propsData: { clients: mockClients }
        });

        expect(wrapper.text()).toContain('Alice');
        expect(wrapper.text()).toContain('Bob');
    });

    it('deletes client when confirmed', async () => {
        confirmSpy.mockReturnValue(true);
        axios.delete.mockResolvedValue({});
        const wrapper = mount(ClientsList, {
            propsData: { clients: mockClients }
        });
        const deleteButton = wrapper.findAll('button.btn-danger').at(0);
        
        await deleteButton.trigger('click');

        expect(axios.delete).toHaveBeenCalledWith('/clients/1');
        expect(wrapper.vm.localClients).toHaveLength(1);
        expect(wrapper.vm.localClients[0].name).toBe('Bob');
    });

    it('does not delete if confirm is cancelled', async () => {
        confirmSpy.mockReturnValue(false);
        const wrapper = mount(ClientsList, {
            propsData: { clients: mockClients }
        });
        const deleteButton = wrapper.findAll('button.btn-danger').at(0);
        
        await deleteButton.trigger('click');

        expect(axios.delete).not.toHaveBeenCalled();
        expect(wrapper.vm.localClients).toHaveLength(2);
    });
});