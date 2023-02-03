<template>
  <b-container fluid>
    <b-modal ref="my-modal" ok-only>
      <b-row>

        <b-col lg="6" class="my-1">
          <b-form-group
              label="Filter"
              label-for="filter-input"
              label-cols-sm="5"
              label-align-sm="right"
              label-size="sm"
              class="mb-0"
          >
            <b-input-group size="sm">
              <b-form-input
                  id="filter-input"
                  v-model="companiesTable.filter"
                  type="search"
                  placeholder="Type to Search"
              ></b-form-input>

              <b-input-group-append>
                <b-button :disabled="!companiesTable.filter" @click="companiesTable.filter = ''">Clear</b-button>
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </b-col>

        <b-col sm="5" md="6" class="my-1">
          <b-form-group
              label="Per page"
              label-for="per-page-select"
              label-cols-sm="6"
              label-cols-md="4"
              label-cols-lg="3"
              label-align-sm="right"
              label-size="sm"
              class="mb-0"
          >
            <b-form-select
                id="per-page-select"
                v-model="companiesTable.perPage"
                :options="companiesTable.pageOptions"
                size="sm"
            ></b-form-select>
          </b-form-group>
        </b-col>

        <b-col sm="7" md="6" class="my-1">
          <b-pagination
              v-model="companiesTable.currentPage"
              :total-rows="companiesTable.totalRows"
              :per-page="companiesTable.perPage"
              align="fill"
              size="sm"
              class="my-0"
          ></b-pagination>
        </b-col>
      </b-row>

      <!-- Main table element -->
      <b-table
          :items="Object.entries(abbreviationToCompanyNameMap)"
          :filter="companiesTable.filter"
          :current-page="companiesTable.currentPage"
          :per-page="companiesTable.perPage"
          stacked="md"
          show-empty
          small
          @filtered="onFiltered"
      >
      </b-table>
    </b-modal>

    <div>

      <div v-if="errors.length > 0">
        <b>Please correct the following error(s):</b>
        <ul>
          <li v-for="(item,messages) in errors">
            {{ item }}
          </li>

        </ul>
      </div>


      <b-card bg-variant="light">

        <b-button @click="show = !show">
          <div v-if="show">
            Hide
          </div>
          <div v-else>
            Show
          </div>
        </b-button>

        <b-form @submit.stop.prevent="onSubmit" @reset="onReset" v-if="show">

          <b-form-group
              id="input-group-1"
              label="Company symbol:"
              label-for="input-1"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm
              content-cols-lg="8"
          >
            <b-input-group>
              <b-form-input
                  id="input-1"
                  v-model="form.companySymbol"
                  type="text"
                  list="my-list-id"
                  placeholder="Enter company symbol"
                  aria-describedby="input-1-live-feedback"
              ></b-form-input>

              <b-input-group-append>
                <b-button
                    id="show-all-companies-button"
                    @click="showAllCompanies"
                    variant="outline-info">Show All
                </b-button>
              </b-input-group-append>

              <datalist id="my-list-id">
                <option v-for="(abbreviation,name) in abbreviationToCompanyNameMap" v-bind:value="name">{{
                    name
                  }}
                </option>
              </datalist>
            </b-input-group>
          </b-form-group>

          <b-form-group
              id="input-group-2"
              label="Period:"
              label-for="input-2"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm
              content-cols-lg="8"
          >
            <b-input-group>

              <b-form-input
                  id="input-2"
                  locale="en"
                  v-model="form.startDate"
                  placeholder="Start date"
                  type="date"
                  aria-describedby="input-2-live-feedback"
              ></b-form-input>
              <b-form-invalid-feedback id="input-2-live-feedback">This is a required field.</b-form-invalid-feedback>
              <b-input-group-append>
                <b-form-input
                    id="input-3"
                    locale="en"
                    v-model="form.endDate"
                    placeholder="End date"
                    type="date"
                    aria-describedby="input-3-live-feedback"
                ></b-form-input>
                <b-form-invalid-feedback id="input-3-live-feedback">This is a required field.</b-form-invalid-feedback>
                <b-dropdown text="Select period" variant="outline-secondary">
                  <b-dropdown-item @click="calculatePeriod('day')">Day</b-dropdown-item>
                  <b-dropdown-item @click="calculatePeriod('week')">Week</b-dropdown-item>
                  <b-dropdown-item @click="calculatePeriod('month')">Month</b-dropdown-item>
                  <b-dropdown-item @click="calculatePeriod('quarter')">Quarter</b-dropdown-item>
                  <b-dropdown-item @click="calculatePeriod('year')">Year</b-dropdown-item>
                </b-dropdown>
              </b-input-group-append>
            </b-input-group>
          </b-form-group>

          <b-form-group
              id="input-group-4"
              label="Email address:"
              label-for="input-4"
              description="We'll never share your email with anyone else."
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm
              content-cols-lg="7"
              aria-describedby="input-4-live-feedback"
          >
            <b-form-input
                id="input-4"
                v-model="form.email"
                type="email"
                placeholder="Enter email"
            ></b-form-input>
            <b-form-invalid-feedback id="input-4-live-feedback">This is a required field.</b-form-invalid-feedback>
          </b-form-group>

          <b-form-group
              id="input-group-5"
              label="Region:"
              label-for="input-5"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm
              content-cols-lg="7"
          >
            <b-form-select
                id="input-4"
                v-model="form.region"
                type="select"
                :options="regions"
            ></b-form-select>
          </b-form-group>

          <b-button type="submit" variant="primary">Submit</b-button>
          <b-button type="reset" variant="danger">Reset</b-button>
        </b-form>
      </b-card>

      <b-row v-show="companyHistoricalData.length">

        <b-col lg="6" class="my-1">
          <b-form-group
              label="Filter"
              label-for="filter-input"
              label-cols-sm="5"
              label-align-sm="right"
              label-size="sm"
              class="mb-0"
          >
            <b-input-group size="sm">
              <b-form-input
                  id="filter-input"
                  v-model="historicalDataTable.filter"
                  type="search"
                  placeholder="Type to Search"
              ></b-form-input>

              <b-input-group-append>
                <b-button :disabled="!historicalDataTable.filter" @click="historicalDataTable.filter = ''">Clear</b-button>
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </b-col>

        <b-col sm="5" md="6" class="my-1">
          <b-form-group
              label="Per page"
              label-for="per-page-select"
              label-cols-sm="6"
              label-cols-md="4"
              label-cols-lg="3"
              label-align-sm="right"
              label-size="sm"
              class="mb-0"
          >
            <b-form-select
                id="per-page-select"
                v-model="historicalDataTable.perPage"
                :options="historicalDataTable.pageOptions"
                size="sm"
            ></b-form-select>
          </b-form-group>
        </b-col>

        <b-col sm="7" md="6" class="my-1">
          <b-pagination
              v-model="historicalDataTable.currentPage"
              :total-rows="historicalDataTable.totalRows"
              :per-page="historicalDataTable.perPage"
              align="fill"
              size="sm"
              class="my-0"
          ></b-pagination>
        </b-col>

        <!-- Main table element -->
        <b-table
            :items="companyHistoricalData"
            :filter="historicalDataTable.filter"
            :current-page="historicalDataTable.currentPage"
            :per-page="historicalDataTable.perPage"
            stacked="md"
            show-empty
            small
            @filtered="onFiltered"
        >
        </b-table>
      </b-row>

    </div>
  </b-container>
</template>

<script>
import moment from "moment";
import * as Validator from 'validatorjs';
import axios from "axios";

export default {
  data() {
    return {
      form: {
        email: '',
        startDate: '',
        endDate: '',
        companySymbol: '',
        region: null
      },
      companiesTable: {
        currentPage: 1,
        perPage: 15,
        filter: '',
        pageOptions: [15, 30, 45, {value: 100, text: "Show a lot"}],
        totalRows: 0,
      },
      historicalDataTable: {
        currentPage: 1,
        perPage: 15,
        filter: '',
        pageOptions: [15, 30, 45, {value: 100, text: "Show a lot"}],
        totalRows: 0,
      },
      companyHistoricalData: [],
      show: true,
      abbreviationToCompanyNameMap: [],
      regions: [
        {value: null, text: 'No region selected'},
        {value: 'AU', text: 'Australia'},
        {value: 'BR', text: 'Brazil'},
        {value: 'CA', text: 'Canada'},
        {value: 'DE', text: 'Germany'},
        {value: 'ES', text: 'Spain'},
        {value: 'FR', text: 'France'},
        {value: 'GB', text: 'United Kingdom'},
        {value: 'HK', text: 'Hong Kong'},
        {value: 'IN', text: 'India'},
        {value: 'IT', text: 'Italy'},
        {value: 'SG', text: 'Singapore'},
        {value: 'US', text: 'United States'},
      ],
      errors: []
    }
  },
  mounted() {
    let that = this
    axios({
      method: 'get',
      url: '/get_company_to_name_map',
      responseType: 'stream'
    })
        .then(function (response) {
          that.abbreviationToCompanyNameMap = JSON.parse(response.data)
          that.companiesTable.totalRows = Object.entries(that.abbreviationToCompanyNameMap).length
        });
  },
  methods: {
    onSubmit() {

      if (this.checkForm()) {
        let that = this
        axios(
            {
              method: 'get',
              url: '/process_company_info',
              params: that.form
            }
        )
            .then(function (response) {
              that.companyHistoricalData = response.data
            }).catch(function (response) {
        });
      }
    },
    showAllCompanies() {
      this.$refs['my-modal'].toggle('#show-all-companies-button')
    },
    checkForm() {
      let result = true
      this.errors = []

      let rules = {
        companySymbol: 'required|alpha',
        email: 'required|email',
        startDate: 'required',
        endDate: 'required'
      };

      let validator = new Validator(this.form, rules)
      let isValidatorPasses = validator.passes();
      let isDatesCorrect = true
      if (moment(this.form.startDate).isAfter(moment(this.form.endDate))) {
        this.errors.push('Start date: The start date is after the end date!')
        isDatesCorrect = false
      }

      if (!isValidatorPasses) {
        Object.entries(validator.errors.errors).forEach(
            ([key, value]) => {
              this.errors.push(key + " : " + value)
            }
        )
      }

      return isValidatorPasses && isDatesCorrect
    },
    onFiltered(filteredItems) {
      this.companyHistoricalData.totalRows = filteredItems.length
      that.companyHistoricalData.currentPage = 1

    },
    onReset(event) {
      event.preventDefault()
      // Reset our form values
      this.form.email = ''
      this.form.startDate = ''
      this.form.endDate = ''
      this.form.companySymbol = ''
      this.form.region = null
      // Trick to reset/clear native browser form validation state
      this.show = false
      this.$nextTick(() => {
        this.show = true
      })
    },
    calculatePeriod(type) {
      const correct_types = [
        'day',
        'week',
        'month',
        'quarter',
        'year',
      ]

      if (correct_types.findIndex((element) => element === type) !== -1) {
        this.form.startDate = moment().startOf(type).format('YYYY-MM-DD')
        this.form.endDate = moment().endOf(type).format('YYYY-MM-DD')
      }
    }
  }
}
</script>