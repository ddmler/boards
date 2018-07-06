jest.mock('axios', () => ({
    put: jest.fn(() => Promise.resolve({ 
        data: {"id":18,"name":"new name","board_id":5,"order":0,"created_at":"2018-07-05 12:36:47","updated_at":"2018-07-05 12:36:51","cards":[{"id":33,"name":"test card 1","board_list_id":18,"order":0,"description":"The description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"},{"id":34,"name":"test card 2","board_list_id":18,"order":1,"description":"","created_at":"2018-07-05 13:52:02","updated_at":"2018-07-05 13:52:02"}]}
    })),
    post: jest.fn(() => Promise.resolve({ 
        data: {"id":35,"name":"new card","board_list_id":18,"order":0,"description":"The description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"}
    })),
    delete: jest.fn(() => Promise.resolve())
}));

import { mount } from '@vue/test-utils';
import List from '@/List.vue';
import axios from 'axios';

describe('List.vue', () => {
    let wrapper;

    beforeEach(async () => {
        wrapper = await mount(List, {
            propsData: {
                list: {"id":18,"name":"def","board_id":5,"order":0,"created_at":"2018-07-05 12:36:47","updated_at":"2018-07-05 12:36:51","cards":[{"id":33,"name":"test card 1","board_list_id":18,"order":1,"description":"The description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"},{"id":34,"name":"test card 2","board_list_id":18,"order":0,"description":"","created_at":"2018-07-05 13:52:02","updated_at":"2018-07-05 13:52:02"}]}
            },
            stubs: ['card']
        });

        jest.resetModules();
        jest.clearAllMocks();
    });

    it('shows the cards', () => {
        expect(wrapper.vm.list.cards.length).toBe(2);
        expect(wrapper.findAll('card-stub').length).toBe(2);
    });

    it('can emit an update-card-order event', () => {
        wrapper.vm.updateOrder();
        expect(wrapper.emitted()['update-card-order']).toBeTruthy();
    });

    it('can remove a card from the view', () => {
        wrapper.vm.deleteCard({"id":33,"name":"test card 1","board_list_id":18,"order":1,"description":"The description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"});
        expect(wrapper.vm.list.cards.length).toBe(1);
    });

    it('can delete itself', async () => {
        await wrapper.find('.list-navs a:nth-child(2)').trigger('click');

        expect(axios.delete).toBeCalledWith('/boardLists/18');
        expect(wrapper.emitted()['delete-list']).toBeTruthy();
    });

    it('can update its name', async () => {
        wrapper.find('.list-navs a:nth-child(1)').trigger('click');

        const input = wrapper.find({ ref: 'edit' });
        input.setValue('new name');
        await input.trigger('keyup.enter');

        expect(axios.put).toBeCalledWith('/boardLists/18', { name: 'new name' });
        expect(wrapper.vm.list.name).toBe('new name');
    });

    it('can create a new card', async () => {
        wrapper.find('.card-footer a').trigger('click');

        const input = wrapper.find({ ref: 'new' });
        input.setValue('new card');
        await input.trigger('keyup.enter');

        expect(axios.post).toBeCalledWith('/cards', { list_id: 18, name: 'new card', order: 2 });
        
        expect(wrapper.vm.list.cards.length).toBe(3);
    });

    it('sorts card by order', () => {
        // expect order reversed compared to propsData
        expect(wrapper.vm.orderedList).toEqual([{"id":34,"name":"test card 2","board_list_id":18,"order":0,"description":"","created_at":"2018-07-05 13:52:02","updated_at":"2018-07-05 13:52:02"},
        {"id":33,"name":"test card 1","board_list_id":18,"order":1,"description":"The description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"}]);
    });

});