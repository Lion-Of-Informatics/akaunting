<template>
    <div class="relative bg-body z-10 rounded-lg shadow-2xl p-10" style="height:675px;">
        <WizardSteps :active_state="active"></WizardSteps>

        <div class="flex flex-col justify-between overflow-y-auto" style="height: calc(100% - 53px)">
            <div v-if="pageLoad" class="absolute left-0 right-0 top-0 bottom-0 w-full h-full bg-white rounded-lg flex items-center justify-center z-50">
                <span class="material-icons form-spin animate-spin text-9xl">data_usage</span>
            </div>
            
            <div class="overflow-x-visible menu-scroll mt-1">
                <form ref="form" class="py-2 align-middle inline-block min-w-full">
                    <table id="tbl-taxes" v-if="taxes.length" class="min-w-full divide-y divide-gray-200">
                        <thead class="thead-light">
                            <tr class="flex items-center px-1">
                                <th class="w-6/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-xs font-medium text-black tracking-wider">
                                    {{ translations.taxes.name }}
                                </th>
                                <th class="w-6/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-right rtl:text-left text-xs font-medium text-black tracking-wider">
                                    {{ translations.taxes.rate }}
                                </th>
                            </tr>
                        </thead>

                        <tbody data-table-body>
                            <tr v-for="(item, index) in taxes" :key="index" data-table-list class="relative flex items-center border-b hover:bg-gray-100 px-1 flex-wrap group">
                                <td :class="current_tab == index ? 'hidden' : ''" class="w-6/12 ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-medium text-black">
                                    {{ item.name }} 
                                </td>
                                <td :class="current_tab == index ? 'hidden' : ''" class="w-6/12 relative ltr:pr-6 rtl:pl-6 py-4 ltr:text-right rtl:text-left whitespace-nowrap text-sm font-medium text-black">
                                    {{ item.rate }}

                                    <div class="absolute ltr:right-12 rtl:left-12 -top-4 hidden items-center group-hover:flex">
                                        <button type="button" class="relative bg-white hover:bg-gray-100 border py-0.5 px-1 cursor-pointer index-actions " @click="onEditItem(item, index)">
                                            <span class="material-icons-outlined text-purple text-lg">edit</span>

                                            <div class="inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm opacity-0 whitespace-nowrap tooltip-content -top-10 -left-2" data-tooltip-placement="top">
                                                <span>{{ translations.taxes.edit }}</span>
                                                <div class="absolute w-2 h-2 -bottom-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-t-0 before:border-l-0" data-popper-arrow></div>
                                            </div>
                                        </button>

                                        <button type="button" class="relative bg-white hover:bg-gray-100 border py-0.5 px-1 cursor-pointer index-actions " @click="onClickDelete(item)">
                                            <span class="material-icons-outlined text-purple text-lg">delete</span>

                                            <div class="inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm opacity-0 whitespace-nowrap tooltip-content -top-10 -left-2" data-tooltip-placement="top">
                                                <span>{{ translations.taxes.delete }}</span>
                                                <div class="absolute w-2 h-2 -bottom-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-t-0 before:border-l-0" data-popper-arrow></div>
                                            </div>
                                        </button>
                                    </div>
                                </td>

                                <td class="w-full p-0 current-tab" v-if="current_tab == index">
                                    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 py-3">
                                        <base-input name="name" data-name="name" :placeholder="translations.taxes.name"
                                        form-classes="sm:col-span-2"
                                        class="required"
                                        v-model="model.name"
                                        :error="onFailErrorGet('name')"
                                        />

                                        <div class="sm:col-span-2"></div>

                                        <base-input name="rate" data-name="rate" :placeholder="translations.taxes.rate"
                                        form-classes="sm:col-span-2"
                                        class="required"
                                        v-model="model.rate"
                                        :error="onFailErrorGet('rate')"
                                        />

                                        <div class="flex justify-end items-center sm:col-span-6">
                                            <base-button class="flex items-center justify-center px-6 py-1.5 text-base rounded-lg bg-transparent hover:bg-gray-100 ltr:mr-2 rtl:ml-2" @click="onCancelItem()">
                                                {{ translations.taxes.cancel }}
                                            </base-button>

                                            <button
                                                type="submit"
                                                :disabled="button_loading"
                                                class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100"
                                                @click="onEditForm(item, $event)"
                                            >
                                                <i v-if="button_loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i> 
                                                <span :class="[{'opacity-0': button_loading}]">
                                                    {{ translations.taxes.save }}
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex flex-col items-center">
                        <div v-if="!taxes.length" class="flex flex-col items-center gap-y-2">
                            <span class="text-dark">
                                {{ translations.taxes.no_taxes }}
                            </span>

                            <span class="text-gray-700">
                                {{ translations.taxes.create_task }}
                            </span>
                        </div>

                        <div v-if="taxes.length" class="w-full border-b hover:bg-gray-100" style="height:53px;">
                            <button type="button" class="w-full h-full flex items-center justify-center text-purple font-medium disabled:bg-gray-200" @click="onAddItem()">
                                <span class="material-icons-outlined text-base font-bold ltr:mr-1 rtl:ml-1">add</span>
                                    {{ translations.taxes.new_tax }}
                            </button>
                        </div>

                       <button v-else type="button" class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100 mt-3" @click="onAddItem()">
                            {{ translations.taxes.new_tax }}
                        </button>
                    </div>
                    
                    <div v-if="new_datas" class="grid sm:grid-cols-4 gap-x-8 gap-y-6 my-3.5 w-full">
                        <base-input :label="translations.taxes.name" name="name" data-name="name" :placeholder="translations.taxes.name"
                        class="sm:col-span-2 required"
                        v-model="model.name"
                        :error="onFailErrorGet('name')"
                        />

                        <base-input :label="translations.taxes.rate" name="rate" data-name="rate"
                        :placeholder="translations.taxes.rate"
                        class="sm:col-span-2 required"
                        v-model="model.rate"
                        :error="onFailErrorGet('rate')"
                        />

                        <div class="flex items-center justify-end sm:col-span-4">
                            <base-button class="flex items-center justify-center px-6 py-1.5 text-base rounded-lg bg-transparent hover:bg-gray-100 ltr:mr-2 rtl:ml-2" @click="new_datas = false">
                                {{ translations.taxes.cancel }}
                            </base-button>

                            <button type="submit" :disabled="button_loading" class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100" @click="onSubmitForm($event)">
                                <i v-if="button_loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i> 
                                <span :class="[{'opacity-0': button_loading}]">
                                    {{ translations.taxes.save }}
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
 
            <div class="flex items-center justify-center mt-5 gap-x-10">
                <base-button class="w-1/2 flex items-center justify-center px-6 py-1.5 text-base rounded-lg bg-transparent hover:bg-gray-100" @click="prev()">
                    {{ translations.taxes.previous }}
                </base-button>

                <base-button class="w-1/2 relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100" @click="next()">
                    {{ translations.taxes.next }}
                </base-button>
            </div>
        </div>

        <notifications></notifications>

        <form id="form-dynamic-component" method="POST" action="#"></form>

        <component v-bind:is="component" @deleted="onDeleteCurrency($event)"></component>
    </div>
</template>

<script>
import AkauntingRadioGroup from "./../../components/AkauntingRadioGroup";
import BulkAction from "./../../plugins/bulk-action";
import MixinsGlobal from "./../../mixins/global";
import WizardAction from "./../../mixins/wizardAction";
import WizardSteps from "./Steps.vue";

export default {
    name: "Taxes",

    mixins: [MixinsGlobal, WizardAction],

    components: {
        AkauntingRadioGroup,
        WizardSteps
    },

    props: {
        taxes: {
            type: [Object, Array],
        },

        translations: {
            type: [Object, Array],
        },

        pageLoad: {
          type: [Boolean, String]
        }
    },

    data() {
        return {
            active: 2,
            bulk_action: new BulkAction(url + "/settings/taxes"),
            add_taxes: true,
            new_add_taxes: false
        };
    },

    methods: {
        onClickDelete(item) {
            this.confirmDelete(
                `${
                  new URL(url).protocol +
                  "//" +
                  location.host +
                  location.pathname +
                  "/" +
                  item.id
                }`,
                this.translations.taxes.title,
                `${
                  this.translations.currencies.title +
                  "&nbsp;" +
                  this.translations.currencies.delete
                } <strong>${item.name}</strong>?`,
                this.translations.taxes.cancel,
                this.translations.taxes.delete
            );
        },

        onDeleteCurrency(event) {
            this.onEjetItem(event, this.taxes, event.tax_id);
        },

        onEditForm(item, event) {
            event.preventDefault();

            this.onSubmitEvent(
                "PATCH",
                url + "/wizard/taxes/" + item.id,
                "type",
                this.taxes,
                item.id
            );
        },

        onNewTax() {
            this.new_add_taxes = true;
            this.add_taxes = false;
        },

        onCancelNewTax() {
            this.new_add_taxes = false;
            this.add_taxes = true;
        },

        onSubmitForm(event) {
            event.preventDefault();
            
            this.onSubmitEvent("POST", url + "/wizard/taxes", "type", this.taxes);

        },

        prev() {
            if (this.active-- > 2);
            this.$router.push("/wizard/currencies");
        },

        next() {
            if (this.active++ > 2);
            this.$router.push("/wizard/finish");
        },
    },
};
</script>
