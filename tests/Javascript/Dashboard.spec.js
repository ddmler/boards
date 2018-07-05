jest.mock('axios', () => ({
    get: jest.fn(() => Promise.resolve({ 
        data: [{"id":5,"name":"test","user_id":1,"created_at":"2018-07-05 12:36:35","updated_at":"2018-07-05 12:36:35","board_lists":[{"id":17,"name":"abc","board_id":5,"order":1,"created_at":"2018-07-05 12:36:41","updated_at":"2018-07-05 12:36:51","cards":[]},{"id":18,"name":"def","board_id":5,"order":0,"created_at":"2018-07-05 12:36:47","updated_at":"2018-07-05 12:36:51","cards":[{"id":33,"name":"test card 1","board_list_id":18,"order":0,"description":"The description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"},{"id":34,"name":"test card 2","board_list_id":18,"order":1,"description":"","created_at":"2018-07-05 13:52:02","updated_at":"2018-07-05 13:52:02"}]}]},{"id":6,"name":"another board","user_id":1,"created_at":"2018-07-05 17:49:04","updated_at":"2018-07-05 17:49:04","board_lists":[]}]
    })),
    post: jest.fn(() => Promise.resolve({ 
        data: {"name":"yet another board","user_id":1,"updated_at":"2018-07-05 17:49:20","created_at":"2018-07-05 17:49:20","id":7}
    })),
    delete: jest.fn(() => Promise.resolve())
}));

import { shallowMount } from '@vue/test-utils';
import Dashboard from '@/Dashboard.vue';
import axios from 'axios';

describe('Dashboard.vue', () => {
    let wrapper;

    beforeEach(async () => {
        wrapper = await shallowMount(Dashboard, {
            stubs: ['router-link']
        });

        jest.resetModules();
        jest.clearAllMocks();
    });

    it('shows the users boards', () => {
        expect(wrapper.findAll('router-link-stub').length).toBe(2);
    });

    it('can create a new board', async () => {
        const input = wrapper.find('form input');
        input.setValue('yet another board');
        await wrapper.find('button').trigger('click');

        expect(axios.post).toBeCalledWith('/boards', { name: 'yet another board' });
        expect(wrapper.findAll('router-link-stub').length).toBe(3);
    });

    it('can delete a board', async () => {
        await wrapper.find('.board-navs a').trigger('click');

        expect(axios.delete).toBeCalledWith('/boards/5');
        expect(wrapper.findAll('router-link-stub').length).toBe(1);
    });

});