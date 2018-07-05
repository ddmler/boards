jest.mock('axios', () => ({
    get: jest.fn(() => Promise.resolve({ 
        data: {"id":5,"name":"test","user_id":1,"created_at":"2018-07-05 12:36:35","updated_at":"2018-07-05 12:36:35","user":{"id":1,"name":"admin","email":"admin@example.com","created_at":"2018-07-03 14:38:30","updated_at":"2018-07-03 14:38:30"},"board_lists":[{"id":18,"name":"def","board_id":5,"order":0,"created_at":"2018-07-05 12:36:47","updated_at":"2018-07-05 12:36:51","cards":[{"id":33,"name":"test card 1","board_list_id":18,"order":0,"description":"The description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"},{"id":34,"name":"test card 2","board_list_id":18,"order":1,"description":"","created_at":"2018-07-05 13:52:02","updated_at":"2018-07-05 13:52:02"}]},{"id":17,"name":"abc","board_id":5,"order":1,"created_at":"2018-07-05 12:36:41","updated_at":"2018-07-05 12:36:51","cards":[]}]}
    }))
}));

import { mount } from '@vue/test-utils';
import Board from '@/Board.vue';
import axios from 'axios';

describe('Board.vue', () => {

    beforeEach(() => {
        jest.resetModules();
        jest.clearAllMocks();
    });

    it('shows the lists', async () => {
        const wrapper = await mount(Board, {
            mocks: {
                $route: { path: '/board/5', params: { id: 5 } }
            },
            stubs: ['list', 'modal']
        });

        expect(axios.get).toBeCalledWith('/boards/5');
        expect(wrapper.vm.board.board_lists.length).toBe(2);
        expect(wrapper.findAll('list-stub').length).toBe(2);
    });

});