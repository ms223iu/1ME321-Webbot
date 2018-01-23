<template lang="html">
  <div class="search">
    <input :class="{ 'inputError' : hasValidationError }" v-model='studentUsername' @keyup.enter='$refs.btnCheck.click()' ref='input' @keydown.space.prevent type="text" placeholder="användarnamn" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" autofocus>
    <button @click='studentCheckRequest' :disabled="isExecuting || hasValidationError" ref="btnCheck">Kontrollera</button>
  </div>
</template>

<script>
import { EventBus } from '../main';

export default {
  data() {
    return {
      studentUsername: '',
      isExecuting: false,
      studentData: ''
    }
  },

  watch: {
    studentData: function() {
      EventBus.$emit('studentDataUpdated', this.studentData);
    },

    isExecuting: function() {
      EventBus.$emit('isExecuting', this.isExecuting);
    }
  },

  computed: {
    hasValidationError() {
      return !this.studentUsername.match("^[a-zA-Z0-9]+$") || this.studentUsername.length != 7;
    }
  },

  methods: {
    studentCheckRequest() {
      this.isExecuting = true;
      this.validationError = false;
      this.studentData = '';
      this.$refs.input.blur();

      axios.post('api/', {
        method: 'student',
        username: this.studentUsername
      }).then((response) => {
        this.studentData = response.data;
        this.isExecuting = false;
      });
    }
  }
}
</script>

<style lang="css" scoped>
.search {
  position: relative;
  margin-top: 1.2em;
  margin-bottom: 0.8em;
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
}

input, button {
    border-width: 0;
    border-radius: 0;
    background-color: #9068be;
    color: white;
    margin: 0;
    padding: 0.7em;
    font-size: 1.2em;
    text-align: center;
    transition: all 0.3s ease-out;
    -webkit-appearance: none;
    -moz-appearance: none;
  }

input:focus, button:focus {
  outline: none;
}

input {
  letter-spacing: 1px;
  text-transform: lowercase;
  width: 100%;
  padding-right: 150px;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
}

button {
  border-left: 1px solid  #845faf;
  position: absolute;
  width: 150px;
  top: 0;
  right: 0;
}

button:hover {
  background-color: #845faf;
}

button:disabled {
  background-color: gray;
  color: lightgray;
}

.inputError {
  box-shadow: 0 0 7px #845faf;
}

::-webkit-input-placeholder {
  color: #6ed3cf;
}

::-moz-placeholder {
  color: #6ed3cf;
}

:-ms-input-placeholder {
  color: #6ed3cf;
}

:-moz-placeholder {
  color: #6ed3cf;
}

@media (hover: none) {
   button:hover {
     background-color: #9068be;
   }

   button:disabled {
     background-color: gray;
     color: lightgray;
   }
}
</style>
