jest.mock('axios', () => ({
    get: jest.fn(() => Promise.resolve({ 
        data: {"id":5,"name":"test","user_id":1,"created_at":"2018-07-05 12:36:35","updated_at":"2018-07-05 12:36:35","user":{"id":1,"name":"admin","email":"admin@example.com","created_at":"2018-07-03 14:38:30","updated_at":"2018-07-03 14:38:30"},"board_lists":[{"id":18,"name":"def","board_id":5,"order":0,"created_at":"2018-07-05 12:36:47","updated_at":"2018-07-05 12:36:51","cards":[{"id":33,"name":"test card 1","board_list_id":18,"order":0,"description":"The description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"},{"id":34,"name":"test card 2","board_list_id":18,"order":1,"description":"","created_at":"2018-07-05 13:52:02","updated_at":"2018-07-05 13:52:02"}]},{"id":17,"name":"abc","board_id":5,"order":1,"created_at":"2018-07-05 12:36:41","updated_at":"2018-07-05 12:36:51","cards":[{"name":"test card 3","order":0,"description":"","board_list_id":17,"updated_at":"2018-07-06 11:55:39","created_at":"2018-07-06 11:55:39","id":35}]}]}
    })),
    post: jest.fn(() => Promise.resolve({ 
        data: {"name":"new list","order":2,"board_id":5,"updated_at":"2018-07-06 11:39:56","created_at":"2018-07-06 11:39:56","id":19,"cards":[]}
    })),
    put: jest.fn(() => Promise.resolve({ 
        data: {"id":5,"name":"new board name","user_id":1,"created_at":"2018-07-05 12:36:35","updated_at":"2018-07-05 12:36:35","user":{"id":1,"name":"admin","email":"admin@example.com","created_at":"2018-07-03 14:38:30","updated_at":"2018-07-03 14:38:30"},"board_lists":[{"id":18,"name":"def","board_id":5,"order":0,"created_at":"2018-07-05 12:36:47","updated_at":"2018-07-05 12:36:51","cards":[{"id":33,"name":"test card 1","board_list_id":18,"order":0,"description":"The description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"},{"id":34,"name":"test card 2","board_list_id":18,"order":1,"description":"","created_at":"2018-07-05 13:52:02","updated_at":"2018-07-05 13:52:02"}]},{"id":17,"name":"abc","board_id":5,"order":1,"created_at":"2018-07-05 12:36:41","updated_at":"2018-07-05 12:36:51","cards":[]}]}
    })),
    patch: jest.fn(() => Promise.resolve())
}));

import { mount } from '@vue/test-utils';
import Board from '@/Board.vue';
import { EventBus } from '@/../EventBus.js';
import axios from 'axios';

describe('Board.vue', () => {
    let wrapper;

    beforeEach(async () => {
        wrapper = await mount(Board, {
            mocks: {
                $route: { path: '/board/5', params: { id: 5 } }
            },
            stubs: ['list', 'modal']
        });

        jest.resetModules();
        jest.clearAllMocks();
    });

    it('shows the lists', () => {
        expect(wrapper.vm.board.board_lists.length).toBe(2);
        expect(wrapper.findAll('list-stub').length).toBe(2);
    });

    it('can update its name', async () => {
        wrapper.find('.board-name').trigger('click');

        let input = wrapper.find({ ref: 'edit' });
        input.setValue('new board name');
        await input.trigger('keyup.enter');

        expect(axios.put).toBeCalledWith('/boards/5', { name: 'new board name' });
        expect(wrapper.vm.board.name).toBe('new board name');
    });


    it('listens for an open-modal event', async () => {
        const spy = jest.spyOn(EventBus, '$on');

        // mount again so our spy gets called
        await mount(Board, {
            mocks: {
                $route: { path: '/board/5', params: { id: 5 } }
            },
            stubs: ['list', 'modal']
        });

        expect(spy).toBeCalled();
    });

    it('can create a new list', async () => {
        const input = wrapper.find('.new-list input');
        input.setValue('new list');
        await wrapper.find('.new-list button').trigger('click');

        expect(axios.post).toBeCalledWith('/boardLists', { board_id: 5, name: 'new list', order: 2 });
        
        expect(wrapper.vm.board.board_lists.length).toBe(3);
    });

    it('can remove a list from the view', () => {
        wrapper.vm.deleteList({"id":17,"name":"abc","board_id":5,"order":1,"created_at":"2018-07-05 12:36:41","updated_at":"2018-07-05 12:36:51","cards":[]});
        expect(wrapper.vm.board.board_lists.length).toBe(1);
    });

    it('sorts lists by order', () => {
        // expect order same as mock data
        expect(wrapper.vm.orderedList[0].name).toBe('def');

        // change order
        wrapper.vm.board.board_lists[0].order = 1;
        wrapper.vm.board.board_lists[1].order = 0;

        // expect order reversed compared to mock data
        expect(wrapper.vm.orderedList[0].name).toBe('abc');
    });

    it('can update the list order', () => {
        let helper;

        expect(wrapper.vm.board.board_lists[0].name).toBe('def');
        expect(wrapper.vm.board.board_lists[0].order).toBe(0);

        // switch list order without setting the order attribute
        helper = wrapper.vm.board.board_lists[0]
        wrapper.vm.board.board_lists[0] = wrapper.vm.board.board_lists[1];
        wrapper.vm.board.board_lists[1] = helper;

        // save new order
        wrapper.vm.updateListOrder();

        expect(axios.patch).toBeCalled();
        expect(wrapper.vm.board.board_lists[0].name).toBe('abc');
        expect(wrapper.vm.board.board_lists[0].order).toBe(0);
        expect(wrapper.vm.board.board_lists[1].name).toBe('def');
        expect(wrapper.vm.board.board_lists[1].order).toBe(1);

    });

    it('can update the card order', () => {
        expect(wrapper.vm.board.board_lists[0].cards[0].name).toBe('test card 1');
        expect(wrapper.vm.board.board_lists[0].cards[0].order).toBe(0);

        // move card from list 1 to list 0 at the first place (moving test card 1 to second place)
        wrapper.vm.board.board_lists[0].cards.unshift(wrapper.vm.board.board_lists[1].cards[0]);
        // remove card from list 1
        wrapper.vm.board.board_lists[1].cards.splice(0,1);

        wrapper.vm.updateCardOrder();

        expect(axios.patch).toBeCalled();
        expect(wrapper.vm.board.board_lists[0].cards[0].name).toBe('test card 3');
        expect(wrapper.vm.board.board_lists[0].cards[0].order).toBe(0);
        expect(wrapper.vm.board.board_lists[0].cards[1].name).toBe('test card 1');
        expect(wrapper.vm.board.board_lists[0].cards[1].order).toBe(1);
    });

});