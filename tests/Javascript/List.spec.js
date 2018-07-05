import { mount } from '@vue/test-utils';
import List from '@/List.vue';

describe('List.vue', () => {

    it('shows the cards', () => {
        const wrapper = mount(List, {
            propsData: {
                list: {"id":18,"name":"def","board_id":5,"order":0,"created_at":"2018-07-05 12:36:47","updated_at":"2018-07-05 12:36:51","cards":[{"id":33,"name":"test card 1","board_list_id":18,"order":0,"description":"The description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"},{"id":34,"name":"test card 2","board_list_id":18,"order":1,"description":"","created_at":"2018-07-05 13:52:02","updated_at":"2018-07-05 13:52:02"}]}
            },
            stubs: ['card']
        });

        expect(wrapper.findAll('card-stub').length).toBe(2);
    });

});