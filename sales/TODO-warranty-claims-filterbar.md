# TODO - Warranty Claims Filter Bar (GET form)

- [ ] Wrap warranty-claims filter bar in a GET form to `route('support.warranty-claims')`.
- [x] Wire filter fields to controller query params: `search`, `status`, `customer`.
- [x] Ensure status options submit lowercase values matching controller filtering (`pending`, `approved`, `rejected`, `completed`) plus `all`.
- [x] Ensure input/select values persist using `{{ $search ?? '' }}`, `{{ $status ?? 'all' }}`, `{{ $customer ?? 'all' }}`.
- [x] Do not modify warranty claims table markup.
- [ ] Manually test: search works, status works, customer works, pagination preserves filters.


