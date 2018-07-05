import { shallowMount } from '@vue/test-utils';
import Dashboard from '@/Dashboard.vue';

describe('Dashboard.vue', () => {

    it('shows the users boards', () => {
        const wrapper = shallowMount(Dashboard, {
            stubs: ['router-link']
        });

        wrapper.setData({
            boards: [
                { id: 1, name: "Board1" },
                { id: 2, name: "Board2" }
            ]
        });

        expect(wrapper.findAll('router-link-stub').length).toBe(2);
    });

});