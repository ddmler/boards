jest.mock('axios', () => ({
    put: jest.fn(() => Promise.resolve({ 
        data: {"id":33,"name":"test card 1","board_list_id":18,"order":0,"description":"The new description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"}
    }))
}));

import { mount } from '@vue/test-utils';
import Modal from '@/Modal.vue';
import axios from 'axios';

describe('Modal.vue', () => {
    let wrapper;

    beforeEach(async () => {
        wrapper = await mount(Modal, {
            propsData: {
                card: {"id":33,"name":"test card 1","board_list_id":18,"order":0,"description":"The description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"}
            }
        });

        jest.resetModules();
        jest.clearAllMocks();
    });

    it('can show the cards description', () => {
        expect(wrapper.find('.modal-body').text()).toContain('The description.');
    });

    it('can show the no description text', () => {
        wrapper.setProps({
            card: {"id":34,"name":"test card 2","board_list_id":18,"order":1,"description":"","created_at":"2018-07-05 13:52:02","updated_at":"2018-07-05 13:52:02"}
        });

        expect(wrapper.find('.modal-body').text()).toContain('No description');
    });

    it('can update the cards description', async () => {
        wrapper.find('small > a').trigger('click');

        const textarea = wrapper.find('textarea');
        textarea.element.value = 'The new description.';
        textarea.trigger('input');
        await wrapper.find('.button').trigger('click');

        expect(axios.put).toBeCalledWith('/cards/' + wrapper.vm.card.id, { description: 'The new description.' });
        expect(wrapper.vm.card.description).toBe('The new description.');
    });

    it('can emit a close-modal event', () => {
        wrapper.find('.modal-close-button').trigger('click');
        
        expect(wrapper.emitted()['close-modal']).toBeTruthy();
    });

});