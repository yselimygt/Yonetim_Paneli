export default function StatPanel({ icon, label, value, tone = 'orange', detail }) {
  return (
    <article className={`admin-stat admin-stat--${tone}`}>
      <div className="admin-stat__icon" aria-hidden="true">
        {icon}
      </div>
      <div>
        <div className="admin-stat__value">{value}</div>
        <div className="admin-stat__label">{label}</div>
        {detail ? <div className="admin-stat__detail">{detail}</div> : null}
      </div>
    </article>
  );
}
