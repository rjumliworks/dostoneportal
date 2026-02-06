<template>
  <PageHeader class="m-3 mt-4" title="Abstract of Bids" />
  <div>
    <b-row class="align-items-center">
      <!-- Left Content -->
      <!-- <b-container card>
        <b-col>
          <th class="font-weight-bold;" style="border: none">
            PROCUREMENT REQUEST NO:
            <u class="text-info">
              <span class="bg-light p-1">
                {{ procurement.code }}
              </span>
            </u>
          </th>
        </b-col>
        
      </b-container> -->

      <!-- Right-Aligned Action Button -->
      <b-col class="text-end">
        <b-dropdown size="sm" variant="success">
          <template #button-content>
            <b>Actions</b>
          </template>
          <b-dropdown-item @click="printBids(procurement)">
            <i class="ri-printer-line align-bottom me-1"></i>
            Print
          </b-dropdown-item>
          <b-dropdown-item
            @click="openBACReso(procurement)"
            v-if="
              procurement.status?.name == 'For BAC Resolution' ||
              (this.procurement.status.name === 'Rebid' &&
                this.procurement.sub_status?.name === 'For BAC Resolution')
            "
          >
            <i class="ri-file-line align-bottom me-1"></i>
            Generate BAC Resolution
          </b-dropdown-item>
        </b-dropdown>
      </b-col>
    </b-row>
  </div>

  <b-tabs class="horizontal-scroll-tabs bg-white" card>
    <template v-for="(bid, bidIndex) in procurement.quotations" :key="bid.id">
      <b-tab v-if="bid.status_id == 36">
        <template #title v-if="bid.status_id == 36">
          {{ bid.supplier.name }}

          <b-badge variant="primary" v-if="getCheckedBidsCount(bid.items) > 0">
            {{ getCheckedBidsCount(bid.items) }}
          </b-badge>
        </template>

        <div v-if="bid.status_id == 36">
          <div
            class="w-100 pt-0 pb-0 mb-3"
            style="height: calc(100vh - 305px); overflow: auto"
            ref="box"
          >
            <div>
              <table style="width: 100%; border-collapse: collapse; border: 1px solid">
                <thead>
                  <tr>
                    <th style="width: 2px">Item No</th>
                    <th style="width: 20px">Status</th>
                    <th style="width: 500px">Item Description</th>
                    <th style="width: 20px">Quantity/Unit</th>
                    <th style="width: 20px">Unit Cost</th>
                    <th style="width: 20px">ABC</th>
                    <th style="width: 20px">Bid Price</th>
                    <th style="width: 20px">Total Bid Price</th>
                    <th style="width: 500px">Technical Proposal / Offer</th>
                    <th style="width: 100px">Delivery Term</th>

                    <th
                      v-if="
                        procurement.status.name == 'For Bids' ||
                        (this.procurement.status.name === 'Rebid' &&
                          this.procurement.sub_status?.name === 'For Bids')
                      "
                    >
                      Recommend Bid For Award?
                    </th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="(item, itemIndex) in bid.items" :key="item.item_id">
                    <td>{{ itemIndex + 1 }}</td>
                    <td>
                      <b-badge
                        v-if="item.status"
                        :variant="getBadgeVariant(item.status.name)"
                        style="color: white"
                      >
                        {{ item.status.name }}
                        <i
                          v-if="item.status.name == 'Not Available for Award'"
                          class="ri-close-line"
                        ></i>
                        <i
                          v-if="item.status.name == 'Available for Award'"
                          class="ri-check-line"
                        ></i>
                        <i v-if="item.status.name == 'Awarded'" class="ri-check-line"></i>
                      </b-badge>
                    </td>
                    <td
                      style="
                        text-align: left;
                        width: break-word;
                        word-break: break-word;
                        white-space: normal;
                      "
                    >
                      <span v-html="item.item.item_description"></span>
                    </td>

                    <td>
                      {{ item.item.item_quantity }}
                      {{
                        item.item.item_quantity > 1
                          ? item.item.item_unit_type.name_long
                          : item.item.item_unit_type.name_short
                      }}
                    </td>
                    <td>{{ formatCurrency(item.item.item_unit_cost) }}</td>
                    <td>
                      {{ formatCurrency(item.item.total_cost) }}
                    </td>

                    <td @click="openEditOffer(item)">
                      <span
                        v-if="item.bid_price > 0"
                        :class="{
                          'text-danger': item.bid_price > item.item.item_unit_cost,
                        }"
                      >
                        <u>{{ formatCurrency(item.bid_price) }}</u>
                      </span>
                      <span v-else-if="item.bid_price == 0">
                        <b
                          ><i class="text-primary"><u>free</u></i></b
                        >
                      </span>
                      <span v-else>
                        <b
                          ><i class="text-primary"><u>not set</u></i></b
                        >
                      </span>
                    </td>
                    <td>
                      <span v-if="item.bid_price == 0">
                        <b><i class="text-primary">free</i></b>
                      </span>
                      <span v-else-if="item.bid_price > 0">
                        {{ formatCurrency(item.item.item_quantity * item.bid_price) }}
                      </span>
                      <span v-else>
                        <b><i class="text-primary">not set</i></b>
                      </span>
                    </td>

                    <td
                      style="
                        text-align: left;
                        width: break-word;
                        word-break: break-word;
                        white-space: normal;
                      "
                    >
                      <div v-html="item.technical_proposal"></div>
                    </td>
                    <td>{{ bid.delivery_term }}</td>

                    <td
                      v-if="
                        procurement.status.name == 'For Bids' ||
                        (this.procurement.status.name === 'Rebid' &&
                          this.procurement.sub_status?.name === 'For Bids')
                      "
                    >
                      <span class="d-flex justify-content-center">
                        <b-form-checkbox
                          v-model="item.is_checked"
                          name="checkbox"
                          class="border-primary bg-primary"
                          :value="true"
                          :disabled="
                            isOtherSupplierChecked(itemIndex, bid) ||
                            item.bid_price == null
                          "
                          @change="handleCheckboxChange(itemIndex, bid)"
                        >
                        </b-form-checkbox>
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>

              <Pagination
                class="ms-2 me-2"
                v-if="meta"
                @fetch="fetch"
                :lists="lists.length"
                :links="links"
                :pagination="meta"
              />
            </div>
          </div>
        </div>
      </b-tab>
    </template>

    <div class="input-group mb-1">
      <b-button
        class="me-2"
        type="button"
        variant="info"
        @click="goBackPage(procurement)"
      >
        <i class="ri-arrow-left-line align-bottom me-1"></i> Back
      </b-button>

      <b-button
        v-if="
          procurement?.status.name == 'For Bids' ||
          procurement?.sub_status.name == 'For Bids'
        "
        class="text-end"
        @click="openRecommendAward()"
        variant="primary"
        block
        >Save Bids For Award</b-button
      >
    </div>
  </b-tabs>

  <Offer ref="editOffer" />
  <Award ref="award" :procurement="procurement" />
  <BACResolution ref="BACReso" :procurement="procurement" :action="'Award'" />
</template>
<script>
import _ from "lodash";
import PageHeader from "@/Shared/Components/PageHeader.vue";
import { useForm } from "@inertiajs/vue3";
import Multiselect from "@vueform/multiselect";
import InputError from "@/Shared/Components/Forms/InputError.vue";
import InputLabel from "@/Shared/Components/Forms/InputLabel.vue";
import TextInput from "@/Shared/Components/Forms/TextInput.vue";
import Checkbox from "@/Shared/Components/Forms/Checkbox.vue";
import Offer from "../Modals/Offer.vue";
import Award from "../Modals/Award.vue";
import BACResolution from "../Modals/BACResolution.vue";

export default {
  components: {
    PageHeader,
    InputError,
    InputLabel,
    TextInput,
    Multiselect,
    Checkbox,
    Offer,
    Award,
    BACResolution,
  },
  props: ["procurement", "dropdowns", "option"],
  data() {
    return {
      currentUrl: window.location.origin,
      lists: [],
      meta: {},
      links: {},
      filter: {
        keyword: null,
      },
      index: null,
      is_checked: false,
      checkedItems: [],
      recommendedBidsForAward: {},
      showBACResoForm: false,
    };
  },

  methods: {
    openEditOffer(item) {
      if (
        this.procurement.status?.name == "For Bids" ||
        (this.procurement.status.name === "Rebid" &&
          this.procurement.sub_status?.name === "For Bids")
      ) {
        this.$refs.editOffer.edit(item);
      }
    },

    openRecommendAward() {
      this.$refs.award.edit(this.checkedItems);
    },

    getCurrentDate() {
      const today = new Date();
      const year = today.getFullYear();
      const month = String(today.getMonth() + 1).padStart(2, "0"); // Months are zero-based
      const day = String(today.getDate()).padStart(2, "0");
      return `${year}-${month}-${day}`;
    },

    formatCurrency(value) {
      return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
      }).format(value);
    },

    getBadgeVariant(status_name) {
      switch (status_name) {
        case "Not Available for Award/Re-award":
          return "danger"; // Maps to Bootstrap's warning variant
        case "For Bids":
          return "info"; // Maps to Bootstrap's info variant
        case "Awarded":
          return "success"; // Maps to Bootstrap's success variant
        default:
          return "secondary"; // Default variant if none match
      }
    },

    goBackPage() {
      this.$inertia.visit("/faims/procurements");
    },

    handleCheckboxChange(itemIndex, currentBid) {
      // If checked → store supplier name
      if (currentBid.items[itemIndex].is_checked) {
        this.checkedItems[itemIndex] = currentBid;
      } else {
        this.not_checked_items[itemIndex] = currentBid;
        delete this.checkedItems[itemIndex];
      }
    },

    isOtherSupplierChecked(itemIndex, currentItem) {
      return this.checkedItems[itemIndex] && this.checkedItems[itemIndex] !== currentItem;
    },

    //count how how many items checked in a supplier
    getCheckedBidsCount(items) {
      return items.filter((item) => item.is_checked).length;
    },

    getTotalBidPrice() {
      this.form.total_bid_price = this.form.bid_price * this.form.item_quantity;
      return this.form.total_bid_price;
    },

    openEditItemBidOffer(bid_item) {
      if (this.procurement.status_id == 4) {
        this.$refs.editItem.edit(bid_item);
      }
    },
    openBACReso(data) {
      this.$refs.BACReso.show("Award");
    },

    printBids(data) {
      window.open(`/faims/procurements/${data.id}?option=print&type=abstract_of_bids`);
    },
  },
};
</script>

<style>
.horizontal-scroll-tabs .nav-tabs .nav-link {
  background-color: white !important;
  color: black !important; /* Ensure text is visible */
  border-bottom: 5px lightgrey solid;
  border-top: 5px lightgrey solid;
  width: 200px;
}

/* Change background when tab is active */
.horizontal-scroll-tabs .nav-tabs .nav-link.active {
  border-bottom: 5px darkblue solid;
  border-top: 5px darkblue solid;
  font-weight: bolder;
  color: darkblue !important;
}
</style>

<style scoped>
td,
th {
  border: 1px solid;
  padding: 5px;
  vertical-align: top;
  text-align: center;
}
</style>
