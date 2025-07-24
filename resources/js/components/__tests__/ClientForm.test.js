import { shallowMount } from '@vue/test-utils'
import ClientForm from '../ClientForm.vue'
import axios from 'axios'

jest.mock('axios')

describe('ClientForm.vue', () => {
    let wrapper

    beforeEach(() => {
        wrapper = shallowMount(ClientForm)
    })

    it('renders all form inputs', () => {
        expect(wrapper.find('#name').exists()).toBe(true)
        expect(wrapper.find('#email').exists()).toBe(true)
        expect(wrapper.find('#phone').exists()).toBe(true)
        expect(wrapper.find('#address').exists()).toBe(true)
        expect(wrapper.find('#city').exists()).toBe(true)
        expect(wrapper.find('#postcode').exists()).toBe(true)
    })

    it('updates data when input changes', async () => {
        const nameInput = wrapper.find('#name')
        nameInput.setValue('Clark Kent')

        await wrapper.vm.$nextTick()

        expect(wrapper.vm.client.name).toBe('Clark Kent')
    })

    it('submits form and redirects on success', async () => {
        const mockUrl = '/clients/123'
        axios.post.mockResolvedValue({ data: { url: mockUrl } })

        wrapper.setData({
            client: {
                name: 'Bruce',
                email: 'bruce@wayne.com',
                phone: '+123 456 789',
                address: 'Batcave',
                city: 'Gotham',
                postcode: '99999'
            },
            errors: {}
        })

        delete window.location
        window.location = { href: '' }

        await wrapper.vm.storeClient()

        expect(axios.post).toHaveBeenCalledWith('/clients', expect.any(Object))
        expect(window.location.href).toBe(mockUrl)
    })

    it('displays validation errors if backend returns 422', async () => {
        axios.post.mockRejectedValue({
            response: {
                status: 422,
                data: {
                    errors: {
                        email: ['The email field is required.'],
                        phone: ['The phone must be valid.']
                    }
                }
            }
        })

        /*
        I had troubles with this test, so in the end it worked by avoiding an await, 
        and using double tick check for refresh 
        */
        wrapper.vm.storeClient()
        await wrapper.vm.$nextTick()
        await wrapper.vm.$nextTick()

        expect(wrapper.vm.errors).toContain('The email field is required.')
        expect(wrapper.vm.errors).toContain('The phone must be valid.')
    })
})
