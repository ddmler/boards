import { mount } from '@vue/test-utils';
import Card from '@/Card.vue';

describe('Card.vue', () => {

    it('can show a description icon', () => {
        const wrapper = mount(Card, {
            propsData: {
                card: {"id":33,"name":"test card 1","board_list_id":18,"order":0,"description":"The description.","created_at":"2018-07-05 13:51:54","updated_at":"2018-07-05 13:52:14"}
            }
        });

        expect(wrapper.find('.fa-comment').isVisible()).toBe(true);
    });

    it('can hide a description icon', () => {
        const wrapper = mount(Card, {
            propsData: {
                card: {"id":34,"name":"test card 2","board_list_id":18,"order":1,"description":"","created_at":"2018-07-05 13:52:02","updated_at":"2018-07-05 13:52:02"}
            }
        });

        expect(wrapper.find('.fa-comment').exists()).toBe(false);
    });

});