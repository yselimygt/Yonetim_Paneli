export default function Panel({ title, action, children, className = '' }) {
  return (
    <section className={`admin-panel ${className}`}>
      <div className="admin-panel__header">
        <h2>{title}</h2>
        {action ? <div className="admin-panel__action">{action}</div> : null}
      </div>
      <div className="admin-panel__body">{children}</div>
    </section>
  );
}
