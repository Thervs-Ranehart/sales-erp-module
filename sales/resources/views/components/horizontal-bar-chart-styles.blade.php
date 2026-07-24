@once
    <style>
        .sales-breakdown-chart__card{position:relative;overflow:hidden;border:1px solid #e2e8f0;border-radius:20px;background:#fff;box-shadow:0 12px 30px rgba(15,23,42,.065);transition:transform .2s ease,box-shadow .2s ease}
        .sales-breakdown-chart__card::before{content:"";position:absolute;inset:0 0 auto;height:4px;background:linear-gradient(90deg,#128b99,#2dd4bf)}
        .sales-breakdown-chart__card:hover{transform:translateY(-2px);box-shadow:0 17px 38px rgba(15,23,42,.1)}
        .sales-breakdown-chart__body{padding:22px 22px 20px}
        .sales-breakdown-chart__header{display:flex;align-items:flex-start;gap:12px;min-height:55px}
        .sales-breakdown-chart__icon{flex:0 0 auto;width:40px;height:40px;display:grid;place-items:center;border:1px solid #ccfbf1;border-radius:12px;color:#0f766e;background:#f0fdfa;font-size:17px}
        .sales-breakdown-chart__header h3{margin:1px 0 4px;color:#172033;font-size:15px;font-weight:750}
        .sales-breakdown-chart__header p{margin:0;color:#7b879a;font-size:11px;line-height:1.45}
        .sales-breakdown-chart__legend{display:flex;flex-wrap:wrap;gap:7px 13px;margin:17px 0 6px;padding:10px 11px;border:1px solid #edf2f7;border-radius:10px;background:#f8fafc}
        .sales-breakdown-chart__legend span{display:inline-flex;align-items:center;gap:5px;color:#64748b;font-size:9px;font-weight:650;white-space:nowrap}
        .sales-breakdown-chart__legend i{width:7px;height:7px;border-radius:50%}
        .sales-breakdown-chart__legend .is-low{background:#ef4444}
        .sales-breakdown-chart__legend .is-mid{background:#f4b400}
        .sales-breakdown-chart__legend .is-high{background:#10b981}
        .sales-breakdown-chart__canvas{position:relative;width:100%;height:230px;margin-top:8px}
        @media(max-width:575px){.sales-breakdown-chart__body{padding:20px 17px 16px}.sales-breakdown-chart__canvas{height:250px}}
    </style>
@endonce
