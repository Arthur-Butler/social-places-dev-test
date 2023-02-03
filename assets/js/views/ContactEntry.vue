<template>
    <div class="page-content">
        <div class="welcome-column d-flex align-center">
            <div class="welcome-message">
                <h1>Welcome</h1>
                <h2>Contact a store today!</h2>
            </div>
        </div>

        <div style="justify-content: center; display: flex; flex-grow: 1; ">
            <div>
                <div id="outer-holder" class="d-flex justify-center">
                    <div id="holder">
                        <div id="lp">
                            <span>Social</span>
                            <img src="images/l-piece.svg"/>
                        </div>
                        <div id="rp">
                            <img src="images/r-piece.svg"/>
                            <span>Places</span>
                        </div>
                    </div>
                </div>

                <v-card id="contactSection" class="contact-block px-5 py-3 mt-4">
                    <v-card-text>
                        <v-form v-model="contactForm" ref="contactForm">
                            <v-text-field
                                v-model="contact.name"
                                :disabled="loading"
                                :error-messages="contactError"
                                :rules="[isRequired]"
                                label="First Name"
                                validate-on-blur
                                ref="nameInput"
                                @input="contactError = null"
                                @keydown.enter="addContact"/>
                            <v-text-field
                                v-model="contact.surname"
                                :disabled="loading"
                                :error-messages="contactError"
                                :rules="[isRequired]"
                                label="Last Name"
                                validate-on-blur
                                ref="surnameInput"
                                @input="contactError = null"
                                @keydown.enter="addContact"/>
                            <v-text-field
                                v-model="contact.phoneNo"
                                :disabled="loading"
                                :error-messages="contactError"
                                :rules="[isRequired]"
                                label="Phone Number"
                                validate-on-blur
                                ref="phoneNoInput"
                                @input="contactError = null"
                                @keydown.enter="addContact"/>
                            <v-text-field
                                v-model="contact.email"
                                :disabled="loading"
                                :error-messages="contactError"
                                :rules="[isRequired]"
                                label="Email"
                                validate-on-blur
                                ref="emailInput"
                                @input="contactError = null"
                                @keydown.enter="addContact"/>
                            <select v-model="contact.store"  ref="storeInput" :rules="[isRequired]">
                                <option disabled value="">Please select a store</option>
                                <option value="1">Store A</option>
                                <option value="2">Store B</option>
                                <option value="3">Store C</option>
                            </select>
                        </v-form>
                    </v-card-text>
                    <div class="d-flex justify-end">
                        <v-btn :loading="loading" color="primary" text @click="addContact">Log In</v-btn>
                    </div>
                </v-card>
            </div>
        </div>
    </div>
</template>

<script>
import {validationRulesMixin} from "~/mixins/validation-rules-mixin";
import httpClient from "~/classes/httpClient";

export default {
    name: "ContactEntry",
    mixins: [validationRulesMixin],
    data() {
        return {
            contact: {
                name:this.$refs.nameInput.value ?? "",
                surname:this.$refs.surnameInput.value ?? "",
                phoneNo: this.$refs.phoneNoInput.value ?? "",
                email: this.$refs.emailInput.value ?? "",
                store: this.$refs.storeInput ?? "",
            },
            contactForm: true,
            loading: false,
            contactError: null
        }
    },
    methods: {
        async addContact() {
            console.log(this.contact);
            this.loading = true;
            if (!this.$refs.contactForm.validate()) {
                this.loading = false;
                return;
            }
            try {
                // The way Symfony is handling the internals of the api/addContact route we can't use FormData
                await httpClient.axiosClient.post(`addContact?name=${this.contact.name}&sname=${this.contact.surname}&phoneNo=${this.contact.phoneNo}&email=${this.contact.email}&store=${this.contact.store}`);
                await this.$router.push('dashboard');
            } catch (error) {
                this.contactError = error.response.data.error;
            } finally {
                this.loading = false;
            }
        },
    },
}
</script>

<style lang="scss" scoped>
.page-content {
    height: 100%;
    display: flex;
    align-items: center;
}

.welcome-message {
    color: white;
    margin: 0 5rem;
    position: relative;

    h1 {
        font-weight: 400;
        font-size: 50px;
        margin-bottom: 1rem;
    }

    h2 {
        font-weight: 400;
        font-size: 30px;
    }
}

.welcome-message:before {
    content: "";
    position: absolute;
    background: inherit;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    box-shadow: inset 0 0 2000px rgba(255, 255, 255, .5);
    filter: blur(10px);
    margin: -20px;
}

.welcome-column {
    background: #2A2342;
    width: 500px;
    height: 100%;
    font-family: "Silka";
}

.contact-block {
    width: 500px;
}

#holder {
    transform: scale(7);
    animation: ease-in-out zoom-out 2s forwards;
    position: relative;
    top: 0;
    width: 444px
}

#outer-holder {
    position: relative;
    height: 120px;
    padding-top: 20px;
    z-index: 3;
}

#contactSection {
    opacity: 1;
    z-index: 1;
}

#contactSection:not(.hide) {
    animation: show 2s forwards;
}

#contactSection.hide {
    animation: hide 120ms forwards;
}

#lp, #rp {
    width: 220px;
    overflow: hidden;
    height: 75px;
    position: relative;
    animation-duration: 600ms;
    animation-timing-function: linear;
    animation-fill-mode: forwards;
    display: inline-block;
}

#lp > span, #rp > span {
    color: #2a2342;
    font-size: 50px;
    position: absolute;
    white-space: nowrap;
    font-family: "Silka";
    animation-duration: 1150ms;
    animation-timing-function: linear;
    animation-fill-mode: forwards;
}

.action {
    #lp > span, #rp > span {
        animation-fill-mode: backwards;
    }
}

#lp > span {
    left: 0;
    animation-name: out-left;
}

#rp > span {
    //padding-right: 0px;
    right: 0;
    animation-name: out-right;
}

#lp > img, #rp > img {
    position: absolute;
    width: 37px;
    height: 75px;
}

#lp > img {
    right: 0;
}

#rp > img {
    left: 0;
}

#lp {
    animation-name: split-up;
    top: -18.75px;
}

#rp {
    animation-name: split-down;
    top: 18.75px;
}

@keyframes zoom-out {
    0% {
        transform: scale(10);
        top: 120px;
    }
    16.6% {
        transform: scale(1);
    }
    /*Waiting for the other animations to end*/
    83% {
        top: 120px;
    }
    100% {
        transform: scale(1);
        top: 0
    }
}

@keyframes split-up {
    0%, 83.3% {
        top: 0;
    }
    100% {
        top: -18.75px;
    }
}

@keyframes split-down {
    0%, 83.3% {
        top: 0;
    }
    100% {
        top: 18.75px;
    }
}

@keyframes out-left {
    0%, 65.2% {
        left: 183px;
    }
    100% {
        left: 0;
    }
}

@keyframes out-right {
    0%, 65.2% {
        right: 183px;
    }
    100% {
        right: 0;
    }
}

@keyframes show {
    0% {
        opacity: 0;
    }
    94% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

@keyframes hide {
    0% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}
</style>
