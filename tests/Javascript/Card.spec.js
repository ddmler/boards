jest.mock('axios', () => ({
    put: jest.fn(() => Promise.resolve({ 
        data: {"id":33,"name":"new name","board_list_id":18,"order":0,"description":"The description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"}
    })),
    delete: jest.fn(() => Promise.resolve())
}));

import { mount } from '@vue/test-utils';
import Card from '@/Card.vue';
import { EventBus } from '@/../EventBus.js';
import axios from 'axios';

describe('Card.vue', () => {
    let wrapper;

    beforeEach(async () => {
        wrapper = await mount(Card, {
            propsData: {
                card: {"id":33,"name":"test card 1","board_list_id":18,"order":0,"description":"The description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"}
            }
        });

        jest.resetModules();
        jest.clearAllMocks();
    });

    it('can show a description icon', () => {
        expect(wrapper.find('.fa-comment').isVisible()).toBe(true);
    });

    it('can hide a description icon', () => {
        wrapper.setProps({
            card: {"id":34,"name":"test card 2","board_list_id":18,"order":1,"description":"","created_at":"2018-07-05 13:52:02","updated_at":"2018-07-05 13:52:02"}
        });

        expect(wrapper.find('.fa-comment').exists()).toBe(false);
    });

    it('can delete itself', async () => {
        await wrapper.find('.card-navs a:nth-child(2)').trigger('click');

        expect(axios.delete).toBeCalledWith('/cards/33');
        expect(wrapper.emitted()['delete-card']).toBeTruthy();
    });

    it('can update its name', async () => {
        wrapper.find('.card-navs a:nth-child(1)').trigger('click');

        let textarea = wrapper.find('textarea');
        textarea.element.value = 'new name';
        textarea.trigger('input');
        await textarea.trigger('keyup.enter');

        expect(axios.put).toBeCalledWith('/cards/33', { name: 'new name' });
        expect(wrapper.vm.card.name).toBe('new name');
    });

    it('can emit an open-modal event', () => {
        const spy = jest.spyOn(EventBus, '$emit');

        wrapper.find('.card-content div div').trigger('click');

        expect(spy).toBeCalled();
    });

});