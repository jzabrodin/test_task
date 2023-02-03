<template>
  <b-container fluid>
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
            <b-form-input
                id="input-1"
                v-model="form.companySymbol"
                type="text"
                placeholder="Enter company symbol"
                aria-describedby="input-1-live-feedback"
            ></b-form-input>
            <b-form-invalid-feedback id="input-1-live-feedback">This is a required field and must be at least 3
              characters.
            </b-form-invalid-feedback>
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
      <b-card class="mt-3" header="Form Data Result">
        <pre class="m-0">{{ errors }}</pre>
      </b-card>
    </div>
  </b-container>
</template>

<script>
import moment from "moment";
import * as Validator from 'validatorjs';
import axios from "core-js/internals/queue";

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
      show: true,
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
    axios.get()
  },
  methods: {
    onSubmit() {

      if (this.checkForm()) {
        alert("Form submitted!");
      }
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
      if ( moment(this.form.startDate).isAfter( moment(this.form.endDate) )) {
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