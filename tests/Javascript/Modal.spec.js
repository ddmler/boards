import { mount } from '@vue/test-utils';
import Modal from '@/Modal.vue';

describe('Modal.vue', () => {

    it('can show the card description', () => {
        const wrapper = mount(Modal, {
            propsData: {
                card: {"id":33,"name":"test card 1","board_list_id":18,"order":0,"description":"The description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"}
            }
        });

        expect(wrapper.find('.modal-body').html()).toContain('The description.');
    });

    it('can show the no description text', () => {
        const wrapper = mount(Modal, {
            propsData: {
                card: {"id":34,"name":"test card 2","board_list_id":18,"order":1,"description":"","created_at":"2018-07-05 13:52:02","updated_at":"2018-07-05 13:52:02"}
            }
        });

        expect(wrapper.find('.modal-body').html()).toContain('No description');
    });

});