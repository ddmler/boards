import { mount } from '@vue/test-utils';
import Register from '@/Register.vue';

describe('Register.vue', () => {

    it('matches the snapshot', () => {
        const wrapper = mount(Register)
        expect(wrapper.html()).toMatchSnapshot();
    });

});