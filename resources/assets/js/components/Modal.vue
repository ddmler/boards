<template>
  <transition name="modal">
    <div class="modal-mask">
      <div 
        class="modal-wrapper" 
        @click.self="closeModal">
        <div class="modal-container">

          <div class="modal-header">
            <a 
              class="modal-close-button" 
              @click="closeModal">
              <i class="fas fa-times fa-lg"/>
            </a>
            <h2>{{ card.name }}</h2>
          </div>

          <div class="modal-body">
            <h3>Description <small><a @click="startEdit">Edit</a></small></h3>
            <span v-if="!editing">{{ card.description || "No description" }}</span>
            <span v-else>
              <textarea 
                v-model="newDesc" 
                class="textarea"/>
              <a 
                class="button is-success" 
                @click="updateCard">Save</a>
              <a @click="stopEdit">
                <i class="fas fa-times fa-lg"/>
              </a>
            </span>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>
<style>
h2 {
  font-size: 1.75rem;
}

h3 {
  font-size: 1.25rem;
}

.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .5s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 600px;
  height: 600px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #242424;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
}

.modal-body {
  margin: 20px 0;
}

.modal-body textarea {
    margin-bottom: 15px;
}

.modal-body i {
    margin-top: 10px;
    margin-left: 15px;
}

.modal-close-button {
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
    props: {
        card: { type: Object, required: true }
    },
    data() {
        return {
            editing: false,
            newDesc: "",
        };
    },
    methods: {
    updateCard() {
        this.editing = false;
        axios
            .put('/cards/' + this.card.id, { description: this.newDesc })
            .then((response) => {
              this.card.description = response.data.description;
            }).catch(() => {});
    },
    closeModal() {
        this.$emit('close-modal');
    },
    startEdit() {
        this.newDesc = this.card.description;
        this.editing = true;
    },
    stopEdit() {
        this.newDesc = "";
        this.editing = false;
    }
}
}
</script>
