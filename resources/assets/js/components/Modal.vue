<template>
    <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper" @click.self="closeModal">
        <div class="modal-container">

          <div class="modal-header">
              {{ card.name }}
          </div>

          <div class="modal-body">
              body
          </div>

          <div class="modal-footer">
              footer
              <button class="modal-default-button" @click="closeModal">
                OK
              </button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>
<style>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 300px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>
<script>
import axios from 'axios';

export default {
    name: 'Modal',
    data() {
        return {
            loading: false,
            error: null,
            editing: false,
        };
    },
    props: {
        card: { type: Object, required: true }
    },
    methods: {
    updateCard() {
        this.error = null;
        this.loading = true;
        this.editing = false;
        axios
            .put('/cards/' + this.card.id, { name: this.newName })
            .then(response => {
                this.loading = false;
            }).catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
        this.card.name = this.newName;
    },
    closeModal() {
        this.$emit('close-modal');
    }
}
}
</script>
